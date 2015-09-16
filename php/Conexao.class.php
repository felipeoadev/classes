<?php
/* Classe Conexao: gerencia conexões com bancos de dados através de arquivos de configuração.
 * Original: TSqlConnection.class.php do livro: PHP - Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */
 
final class Conexao
{
 /* método  __construct(): não existirão instâncias de Conexao, por isto estamos marcando como private */
 private function __construct() {}
 
 /* método abrir(): recebe o nome do banco de dados e instancia o objeto PDO correspondente */
 public static function abrir($nome, $opcao = false)
 {
  //verifica se existe o arquivo de configuração para este banco de dados
  if (file_exists($nome))
  {
   //lê o INI e retorna o array
   $db = parse_ini_file($nome, true);
  }
  else
  {
   //se não existir, lança um erro
   throw new Exception("Arquivo '".$nome."' não encontrado");
  }
        
  if (!$opcao)
  {  
   $user  = $db["CONEXAO"]["user"];
   $base  = $db["CONEXAO"]["base"];
   $host  = $db["CONEXAO"]["host"];
   $schema = $db["CONEXAO"]["schema"];      
   $senha = base64_decode($db["CONEXAO"]["senha"]);
   $tipo  = $db["CONEXAO"]["tipo"];  
  }
  else
  {   
   $user  = $db["CONEXAO2"]["user"];
   $base  = $db["CONEXAO2"]["base"];
   $host  = $db["CONEXAO2"]["host"];  
   $senha = base64_decode($db["CONEXAO2"]["senha"]);
   $tipo  = $db["CONEXAO2"]["tipo"];    
   $schema = $db["CONEXAO2"]["schema"];     
  }
              
  try
  {
   //descobre qual o tipo (driver) de banco de dados a ser utilizado
   switch($tipo)
   { 
    case 'pgsql':
     $conn = new PDO("pgsql:dbname={$base};host={$host}", $user, $senha);
     break;
    case 'mysql':
     $conn = new PDO("mysql:host={$host};port=3306;dbname={$base}", $user, $senha);
     break;
    case 'sqlite':
     $conn = new PDO("sqlite:={$base}");
     break;
    case 'ibase':
     $conn = new PDO("firebird:dbname={$base}", $user, $senha);
     break;
    case 'oci8':
     $conn = new PDO("oci:dbname={$base}", $user, $senha);
     break;
    case 'mssql':
     //$conn = new PDO("dblib:host={$host};dbname={$base}", $user, $senha);
     $conn = new PDO("dblib:host={$host}{$schema};dbname={$base}", $user, $senha);	 
     break;   
   }
   
   //define para que o PDO lance exceções na ocorrência de erros
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
   //retorna o objeto instanciado
   return $conn;
  }
  catch(Exception $erro)
  {	  
   echo "Conexao.class.php -> ERRO [".htmlentities($erro->getMessage())."]";
  }
 }
} 
?>
