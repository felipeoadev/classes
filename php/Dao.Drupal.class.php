<?php
class Dao
{
    protected $data;
    private $query;
    private $is_new;
    private $table;
    private $primaryKey;
    private $bd;
    private $properties;
    private $name;
    private $label;

    //Metodo construtor da classe
    public function __construct()
    {
        $this->data = array();
        $this->query = NULL;
        $this->is_new = TRUE;
        $this->bd = RADIO;

        /* Não usa nesse modelo de classe
        if($this->properties)
        {
            foreach($this->properties as $property)
            {
                $this->{$property} = NULL;
            }
        }*/
    }

    //Metodos Getters
    public function __get($property)
    {
        return utf8_encode($this->{$property});
    }

    //Metodos Setters
    public function __set($property, $value)
    {
        //$filtro = new Filtro();
        //$this->{$property} = $filtro->transformar($value);
        $this->{$property} = $value;
    }
    
    public function getLabel() 
    {
        return $this->label;
    }    

    /* Carrega os dados de acordo com a procedure e chave primária
     * Parametro: primaryKey
     * Retorno: Os dados da procedure
     */
    public function load($primaryKey)
    {
        db_set_active($this->bd);
        $result = db_query("EXEC SPobter{$this->table} :{$this->primaryKey}", array(":{$this->primaryKey}" => $primaryKey))->fetchAssoc();
        db_set_active(PADRAO);

        if ($result)
        {
            foreach ($result as $property => $value)
            {
                $this->{$property} = $value;
            }
            $this->is_new = FALSE;
        }
    }

    /* Lista os dados de acordo com a procedure
     * Parametro: $op pode ser L ou O
     * Retorno: Os dados da procedure
     */
    public function listar($op='L')
    {
        db_set_active(RADIO);
        try
        {
            $result = db_query("EXEC SPlistar{$this->table}");
            if($op == 'O')
              $result = $result->fetchObject();
            else
              $result = $result->fetchAll();
        }
        catch (PDOException $e)
        {
          $result = NULL;
        }

        db_set_active(PADRAO);
        return $result;
    }

    /* Monta o array com os atributos da classe para executar uma operacao (Insert, Delete, Update)
     * Parametro:
     * Retorno:
    */
    function getData()
    {
        $query = NULL;
        $data = array();

        foreach($this->properties as $property)
        {
            if($this->is_new && $property == $this->primaryKey)
            {
                continue;
            }
            else
            {
                if(is_null($this->$property))
                {
                    $query .= empty($query) ? 'NULL' : ',NULL';
                }
                else
                {
                    $query .= empty($query)?':'.$property:',:'.$property;
                    $data[':'.$property] = $this->{$property};
                }
            }
        }

        $this->query = $query;
        $this->data = $data;
    }

    /* Retorno os atributos da classe
     * Parametro:
     * Retorno:
    */
    public function getProperties()
    {
        return $this->properties;
    }

    /* Insere os atributos da classe automaticamente conforme a tabela
     * Parametro:
     * Retorno:
    */
    public function setProperties()
    {
        db_set_active(RADIO);
        $result = db_query("SELECT properties=syscolumns.name FROM syscolumns LEFT JOIN sysobjects ON sysobjects.id = syscolumns.id WHERE sysobjects.name = :table;", array(":table" => $this->table))->fetchCol();
        db_set_active(PADRAO);

        $this->properties = $result;
        $this->primaryKey = $result[0];
    }


     /* Insere ou Atualiza um registro no Banco de Dados
     * Parametro:
     * Retorno:
     */
    public function save()
    {
        db_set_active($this->bd);
        $this->getData();
        $erro = array();

        $procedure = new ProcedureSql;
        $procedure->setEntidade("SPincluir{$this->table}");
        $procedure->addParametro($this->data);

        try
        {
            if ($this->is_new)
            {
                $erro['i'] = db_query($procedure->getInstrucao(1));
                $this->is_new = FALSE;
            }
            else
            {
                $erro['u'] = db_query("EXEC SPalterar{$this->table} ".$this->query, $this->data);
            }
        }
        catch (PDOException $e)
        {
            $erro['e'] = $e->getMessage();
            $erro['er'] = $e;
        }

        db_set_active(PADRAO);
        return $erro;
    }

    /* Apaga um registro no Banco de Dados
     * Parametro:
     * Retorno:
    */
    public function delete()
    {
        if (!$this->is_new)
        {
            db_set_active($this->bd);
            try
            {
                $erro['d'] = db_query("EXEC SPremover{$this->table} :{$this->primaryKey}", array(":{$this->primaryKey}" => $this->{$this->primaryKey}));
            }
            catch (PDOException $e)
            {
                $erro['e']  = $e->getMessage();
                $erro['er'] = $e;

            }

            db_set_active(PADRAO);
            $this->is_new = TRUE;
            return $erro;
        }
    }

    /* Converte data conforme data é passada por parametro
     * Se data = dd/mm/yyyy converte para yyyy-mm-dd
     * Se data = yyyy-mm-dd converte para dd/mm/yyyy
    */
    public function converteData($data)
    {
        if ( ! strstr( $data, '/' ) )
        {
                // $data está no formato ISO (yyyy-mm-dd) e deve ser convertida
                // para dd/mm/yyyy
                sscanf( $data, '%d-%d-%d', $y, $m, $d );
                return sprintf( '%d/%d/%d', $d, $m, $y );
        }
        else
        {
                // $data está no formato brasileiro e deve ser convertida para ISO
                sscanf( $data, '%d/%d/%d', $d, $m, $y );
                return sprintf( '%d-%d-%d', $y, $m, $d );
        }

        return false;
    }


 }
 ?>
