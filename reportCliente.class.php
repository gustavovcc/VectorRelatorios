<?php  
  require_once "conexao/conexao.php";  
  require_once "MPDF/mpdf.php";  
 
  class reportCliente extends mpdf{  
 
    // Atributos da classe  
    private $pdo  = null;  
    private $pdf  = null;
    private $css  = null;  
    private $titulo = null; 
 
    /*  
    * Construtor da classe  
    * @param $css  - Arquivo CSS  
    * @param $titulo - Título do relatório   
    */  
    public function __construct($css, $titulo) {  
      $this->pdo  = Conexao::getInstance();  
      $this->titulo = $titulo;  
      $this->setarCSS($css);  
    }
  
    /*  
    * Método para setar o conteúdo do arquivo CSS para o atributo css  
    * @param $file - Caminho para arquivo CSS  
    */  
    public function setarCSS($file){  
     if (file_exists($file)):  
       $this->css = file_get_contents($file);  
     else:  
       echo 'Arquivo inexistente!';  
     endif;  
    }  
 
    /*  
    * Método para montar o Cabeçalho do relatório em PDF  
    */  
    protected function getHeader(){  
       $data = date('j/m/Y');  
       $retorno = "<table class=\"tbl_header\" width=\"1000\">  
               <tr>  
                 <td align=\"left\">Biblioteca mPDF</td>  
                 <td align=\"right\">Gerado em: $data</td>  
               </tr>  
             </table>";  
       return $retorno;  
     }  
 
     /*  
     * Método para montar o Rodapé do relatório em PDF  
     */  
     protected function getFooter(){  
       $retorno = "<table class=\"tbl_footer\" width=\"1000\">  
               <tr>  
                 <td align=\"left\">Vector Contact Center<a href=''>www.vectorcontactcenter.com.br</a></td>  
                 <td align=\"right\">Página: {PAGENO}</td>  
               </tr>  
             </table>";  
       return $retorno;  
     }  
 
    /*   
    * Método para construir a tabela em HTML com todos os dados  
    * Esse método também gera o conteúdo para o arquivo PDF  
    */  
    private function getTabela(){  
      $color  = false;  
      $retorno = "";  
 
      $retorno .= "<h2 style=\"text-align:center\">{$this->titulo}</h2>";  
      $retorno .= "<table border='1' width='1000' align='center'>  
           <tr class='header'>  
             <th>CONTRATO</td>  
             <th>GRUPO</td>
             <th>RESULTADO</td>
             <th>CIDADE</td>  
             <th>TELEFONE RECEBIDO</td>  
             <th>CEL1</td> 
             <th>CEL2</td>
             <th>CEL3</td> 
             <th>CEL4</td>
             <th>CEL5</td>
             <th>CEL6</td>
             <th>CEL7</td>
             <th>CEL8</td>
             <th>CEL9</td>  
             <th>DATA DO ATENDIMENTO</td>
             <th>DATA DO AGENDAMENTO</td>              
           </tr>";  
 
      $sql = "SELECT * FROM TAB_CONTRATO";  
      foreach ($this->pdo->query($sql) as $reg):  
         $retorno .= ($color) ? "<tr>" : "<tr class=\"zebra\">";  
         $retorno .= "<td class='destaque'>{$reg['ntc']}</td>";  
         $retorno .= "<td>{$reg['grupo']}</td>";  
         $retorno .= "<td>{$reg['resultado']}</td>";  
         $retorno .= "<td>{$reg['cidade']}</td>";  
         $retorno .= "<td>{$reg['telefonerecebido']}</td>";  
         $retorno .= "<td>{$reg['cel1']}</td>";
         $retorno .= "<td>{$reg['cel2']}</td>";
         $retorno .= "<td>{$reg['cel3']}</td>";
         $retorno .= "<td>{$reg['cel4']}</td>";
         $retorno .= "<td>{$reg['cel5']}</td>";
         $retorno .= "<td>{$reg['cel6']}</td>";
         $retorno .= "<td>{$reg['cel7']}</td>";
         $retorno .= "<td>{$reg['cel8']}</td>";
         $retorno .= "<td>{$reg['cel9']}</td>";
         $retorno .= "<td>{$reg['data']}</td>";
         $retorno .= "<td>{$reg['datarecolhimento']}</td>";
       $retorno .= "<tr>";  
       $color = !$color;  
      endforeach;  
      $retorno .= "</table>";  
      return $retorno;  
    } 
 
    /*   
    * Método para construir o arquivo PDF  
    */  
    public function BuildPDF(){  
     $this->pdf = new mPDF('utf-8', 'A4-L');  
     $this->pdf->WriteHTML($this->css, 1);  
     $this->pdf->SetHTMLHeader($this->getHeader());  
     $this->pdf->SetHTMLFooter($this->getFooter());  
     $this->pdf->WriteHTML($this->getTabela());   
    }   
 
    /*   
    * Método para exibir o arquivo PDF  
    * @param $name - Nome do arquivo se necessário grava-lo  
    */  
    public function Exibir($contrato = null) {  
     $this->pdf->Output($contrato, 'I');  
    }  
  }   