<?php
/* Classe Logger: esta classe provê uma interface abstrata para definição de algoritmos de LOG
 * Original: TLogger.class.php do livro: PHP - Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */
 
abstract class Logger
{
 protected $arquivo;   //local do arquivo de LOG
 
 /* Método __construct(): instância um logger  
  * @param $arquivo = local do arquivo de LOG
  */  
 public function __construct($arquivo) 
 {
  $this->arquivo = $arquivo;
  
  //reseta o conteudo do arquivo
  //file_put_contents($arquivo, '');
 }
 
 //define o método escrever como obrigatório
 abstract function escrever($mensagem);
} 
?>