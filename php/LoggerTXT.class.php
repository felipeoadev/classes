<?php
/* Classe LoggerTXT: implementa o algoritmo de LOG no banco de dados MySQL
 * Original: TLoggerHTML.class.php do livro: PHP - Programando com Orientação a Objetos 
 * Autor: Pablo Dall'Oglio
 */

class LoggerTXT extends Logger
{
 /* Método escrever(): escreve uma mensagem no arquivo de LOG.
    @param $mensagem = mensagem a ser escrita.
  */
 public function escrever($mensagem)
 {
  $hora = date("H:i:s");
  
  //monta a string
  $texto = $hora." :: ".$mensagem."\r\n\r\n";
  
  $handler = fopen($this->arquivo, "a");
  
  fwrite($handler, $texto);
  fclose($handler);
 }
}
?>