<?php
/* Classe Transacao: esta classe provê os métodos necessários para manipular transações
 * Original: TSqlTransaction.class.php do livro: PHP - Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */
 
final class Transacao
{
 private static $conn;	 //conexão ativa
 private static $logger; //objeto de LOG
 
 /* Método __construct(): está declarado como private para impedir que se crie instâncias de TTransaction  */
 private function __construct() {}
  
 /* Método abrir(): abre uma transação e uma conexão com o BD
  * @param $database = nome do banco de dados
  */
 public static function abrir($nome, $opcao = false)
 {
  //abre uma conexão e armazena na propriedade estática $conn
  if (empty(self::$conn))
  {
   self::$conn = Conexao::abrir($nome, $opcao);
   
   //inicia a transação
   self::$conn->beginTransaction();
   
   //desliga o log de SQL
   self::$logger = NULL;
  }
 }
 
 /* Método get(): retorna a conexão ativa */
 public static function get()
 {
  //retorna a conexão ativa
  return self::$conn;
 }
 
 /* Método rollback(): desfaz todas operações realizadas na transação */
 public static function rollback()
 {
  if (self::$conn)
  {
   //desfaz as operações realizadas e fecha a transacao
   self::$conn->rollback();
   self::$conn = NULL;
  }
 } 
 
 /* Método fechar(): aplica todas operações realizadas e fecha a transação */
 public static function fechar()
 {
  if (self::$conn)
  {
   //aplica as operações realizadas
   self::$conn->commit();
   self::$conn = NULL;
  }
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