<?php  
/*===========================CLASSE WebService============================*/

 //Latitude, Longitude
 //-22.6974220,-45.1188510 - Campus I
 //-22.7670870,-45.1013420 - Campus II
 class WebService
 {
     private $url  = "http://192.168.50.25:8080/ciWebServices/resources";
     private $url2 = "http://143.107.119.59:8080/ciWebServices/resources";
     private $conteudo;
     private $json;
     //private $properties;
                      
     public function __construct() 
     {
         /*if($this->properties)
         {
             foreach($this->properties as $property) 
             {
                 $this->{$property} = NULL;
             }               
         } */
     }
     
     public function __destruct() 
     {
         foreach ($this as $key => $value) 
         {
             unset($this->$key);
         }
          
         foreach(array_keys(get_defined_vars()) as $var) 
         {
             unset(${"$var"});
         }
          
         unset($var);
      }     
    
     /*public function __get($property) 
     {
         return utf8_encode($this->{$property});
     }

     public function __set($property, $value) 
     {
         $this->{$property} = $value;
     }*/
    
    
     public function listar($tipo, $codigo)
     {
         $codigo         = str_replace(' ', '%20', $codigo);
         $opts           = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
         $context        = stream_context_create($opts);
         $this->conteudo = file_get_contents($this->url."/".$tipo."/listar/".$codigo, false, $context);
         $this->json     = json_decode($this->conteudo, true);
         return $this->json;
     }    
    
     public function obter($tipo, $codigo)
     {
         $codigo         = str_replace(' ', '%20', $codigo);
         $opts           = array('http'=>array('header' => "User-Agent:MyAgent/1.0\r\n"));
         $context        = stream_context_create($opts);
         $this->conteudo = file_get_contents($this->url."/".$tipo."/obter/".$codigo, false, $context);
         $this->json     = json_decode($this->conteudo, true);
         return $this->json;        
     }
     
    /* Busca o codigo ddd do aluno pelo codigo da localidade
     * Parametro: codloc
     * Retorno: Código DDD da localidade se o codigo da localidade não for vazio
     */       
     public function obterDDD($codloc)
     {
         if (!empty($codloc))                 
         {
             $retorno = $this->obter("localidade", '/1/'.$codloc);
             return '('.$retorno[0]->codddd.')';
         }
     }
     
    /* Busca o curso do aluno pelo codigo do curso
     * Parametro: codcur
     * Retorno: O nome do curso de graduação
     */     
     public function obterCursoGR($codcur)
     {
         $retorno = $this->obter('cursogr', $codcur);          
         return $retorno[0]['nomcur'];         
     }
     
     
    /* Busca a situação da matricula do aluno pelo codigo da pessoa
     * Parametro: codpes
     * Retorno: A situação do aluno no semestre/ano de acordo com a tabela HIDROGENIO.dbo.SITALUNOATIVOGR 
     * (Matriculado (M), Trancado (T), Suspenso (S), Pendente (P), Ativo (A) ou Reativado (R). Ativo ou Reativado indicam que o aluno não está matriculado neste semestre)
     */
     public function obterSituacaoMatricula($codpes, $atual = false)
     {
         if ($atual)
         {                  
            $ano      = date("Y");
            $semestre = date("m") <= 7 ? 1 : 2;                          
            $retorno = $this->obter('alunogr/situacao', $codpes.'/'.$ano.$semestre);
         }
         else
         {
             $retorno = $this->obter('alunogr/situacao', $codpes);         
         }
         
         return $retorno;
         
         
        /* if ($retorno[0]->staalu == 'M')
         {
             $retorno[0]->staalu = utf8_encode('Matriculado');
         }
         else if ($retorno[0]->staalu == 'T')
         {
             $retorno[0]->staalu = utf8_encode('Trancado');
         }
         else if ($retorno[0]->staalu == 'S')
         {
             $retorno[0]->staalu = utf8_encode('Suspenso');
         }         
         else if ($retorno[0]->staalu == 'P')
         {
             $retorno[0]->staalu = utf8_encode('Pendente');
         }
         else if ($retorno[0]->staalu == 'A')
         {
             $retorno[0]->staalu = utf8_encode("Ativo (Sem matrícula no semestre ".$semestre."/".$ano.")");
         }
         else if ($retorno[0]->staalu == 'R')
         {
             $retorno[0]->staalu = utf8_encode("Reativado (Sem matrícula no semestre ".$semestre."/".$ano.")");
         } 
         else 
         {
             $retorno[0]->staalu = 'Aluno com vínculo encerrado';
         }*/
     }

    /* Busca a localidade pelo codigo da localidade
     * Parametro: valor, tipo (1 por Codigo e 2 por Nome),  estado (Opcional)
     * Se estado for true retorna localidade/uf senao retorna localidade
     */
    public function obterLocalidade($valor, $tipo, $estado = false)
    {        
         $retorno = $this->obter('localidade', $tipo."/".$valor);     
         
         if ($estado)
         {
             if (!empty($retorno[0]['sglest']))  
             {
                 return $retorno[0]['cidloc']."/".$retorno[0]['sglest'];    
             }   
         }
         else
         {
             return $retorno[0];    
         }      
    }
    
    /* Busca todos os paises
     * Parametro: 
     * Retorno: Lista com codigo e nome do pais      
     */    
    public function listarPais()
    {
         $retorno = $this->listar('pais');  
              
         foreach ($retorno as $linhas)
         {
             $pais[$linhas->codpas] = $linhas->nompas;
         }
         
        return $pais;  
    }

    /* Lista todos os estados, se o pais for passado lista só os estadosdo pais 
     * Parametro: $codpas = (Opcional)
     * Retorno: Lista com codigo e nome do pais      
     */
    public function listarEstado($codpas)
    {
         $retorno = $this->listar('estado', $codpas);         

         foreach ($retorno as $linhas)
         {
             $estado[$linhas['sglest']] = $linhas['nomest'];
         }
         
        return $estado;  
    }
    
    /* Lista todas as localidade de acordo com o pais e uf 
     * Parametro: $codpas,  $sglest
     * Retorno: Lista com codigo e nome da localidade      
     */
    public function listarLocalidade($codpas, $sglest)
    {
         $retorno = $this->listar('localidade', $codpas.'/'.$sglest);
         
         foreach ($retorno as $linhas)
         {
             $localidade[$linhas['codloc']] = $linhas['cidloc'];
         }
         
        return $localidade;  
    }       
    
    /* Busca todos os estado civil
     * Parametro: 
     * Retorno: Lista com codigo e estado civil   
     */    
    public function listarEstadoCivil()
    {
         $retorno = $this->listar('estadocivil');
         
         foreach ($retorno as $linhas)
         {
             $civil[$linhas->codestcivspp] = $linhas->estciv;
         }
         
        return $civil;  
    }   
    
    /* Busca todos os estado civil
     * Parametro: 
     * Retorno: Lista com codigo e estado civil   
     */    
    public function listarTiposIdentidade()
    {
         $retorno = $this->listar('tipoidentidade');
         
         foreach ($retorno as $linhas)
         {
             $identidade[$linhas->tipdocidf] = $linhas->nomdocidf;
         }
         
        return $identidade;  
    } 
        
    /* Busca a situação da matricula do aluno pelo codigo da pessoa
     * Parametro: codpes
     * Retorno: Lista com as matriculas do Aluno
    
    public function listarSituacaoAlunoGR($codpes)
    {
        $retorno = $this->obter('alunogr/situacao', $codpes);         
        return $retorno;
    } */    
    
    /* Busca todas as pessoa da tabela EEL_PESSOA
     * Parametro: 
     * Retorno: Lista as pessoas da EEL
     */    
    public function listarPessoa()
    {
        $retorno = $this->listar('pessoa');         
        return $retorno[0];
    }  
    
    /* Busca a pessoa pelo codigo da pessoa ou por nome
     * Parametro: valor, tipo (1 por Codigo e 2 por Nome)
     * Retorno: Lista os dados da pessoa
     */
    public function obterPessoa($valor, $tipo)
    {
         $retorno = $this->obter('pessoa', $tipo."/".$valor);      
         return $retorno[0];
    }    
 }
