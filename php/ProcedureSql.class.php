<?php
 /* Classe ProcedureSql: essa classe provê meios para criação de databases
  * Original: TSqlSelect.class.php do livro: PHP - Programando com Orientação a Objetos 
  * Autor: Pablo Dall'Oglio
  * Novo Autor: Felipe de Oliveira Arruda
  */

 final class ProcedureSql extends InstrucaoSql
 {
     private $parametros;    //array de parametros que serão enviados
     private static $logger; //objeto de LOG
 
    /* Método addParametros(): adiciona os parametrso que serão passados a PROCEDURE
     * @param $coluna = coluna da tabela
     */
    public function addParametro($parametro)
    {
        $filtro = new Filtro();    
            
        if (is_array($parametro))
        {
            //percorre o array
            foreach ($parametro as $x)
            {
                $this->parametros[] = $filtro->transformar($x);                    
            }
        }
        else 
        {
            //adiciona a coluna no array
            $this->parametros[] = $filtro->transformar($parametro);
        } 
        
        $filtro = NULL;           
    }
 
    /* Método getInstrucao(): retorna a instrução CALL em forma de string 
     * @param $tipo = tipo de Banco de Dados (0 = MySQL, 1 = SQL Server)
     * */ 
    public function getInstrucao($tipo)
    {
        if ($tipo == 0)
        {
            $this->sql = 'CALL '. $this->entidade .'(';
    
            //monta os parametros que foram passados
            if (sizeof($this->parametros) > 0)
            {  
                $this->sql .= implode(', ', $this->parametros);
            }
  
            $this->sql .= ');';              
        }    
        else if ($tipo == 1)
        {
            $this->sql = 'EXEC '. $this->entidade.' ';
    
            //monta os parametros que foram passados
            if (sizeof($this->parametros) > 0)
            {  
                $this->sql .= implode(', ', $this->parametros);
            }           
        }
        
        return $this->sql;            	 
    } 
 
    /* Método setLogger(): define qual a estratégia (algoritmo de LOG a ser usado)*/
    public static function setLogger(Logger $logger)
    {
        self::$logger = $logger;
    }
 
    /* Método log(): armazena uma mensagem no arquivo de LOG baseada na estratégia ($logger) atual.*/
    public static function log($mensagem)
    {
        //verifica se existe um logger
        if(self::$logger)
        {
            self::$logger->escrever($mensagem);
        }
    } 
  } 
?>