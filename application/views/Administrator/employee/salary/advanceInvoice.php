<?php
$branchID = $this->session->userdata('BRANCHid');
$PamentID = $this->session->userdata('PamentID');
$advanec = $this->db->query("
    select 
        sa.*, 
        e.*,
        m.month_name
    from tbl_salary_advance sa 
    left join tbl_employee e on e.Employee_SlNo = sa.employee_id 
    left join tbl_month m on m.month_id = sa.month_id
    where sa.id = ?
", $PamentID)->row();

$EMPID = $advanec->employee_id;
$paid = $advanec->amount;
$type = $advanec->type;


$Custid = $advanec->employee_id;

?>

<div class="content_scroll" style="width: 850px; ">
<a  id="printIcon" style="cursor:pointer"> <i class="fa fa-print" style="font-size:24px;color:green"></i> Print</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="<?php echo base_url();?>customerPaymentPage" title="" class="buttonAshiqe">Go Back</a>
    <div id="reportContent">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12">
                <h6 style="background:#ddd; text-align: center; font-size: 18px; font-weight: 900; padding: 5px; color: #bd4700;">Advance Salary Invoice</h6>
            </div>
        </div>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12">
                <table style="width: 100%;">
                    <tr>
                        <td style="font-size: 13px; font-weight: 600;"> TR. Id: <?php echo $advanec->code; ?></td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; font-weight: 600; ">TR. Date: <?php echo $advanec->date; ?> </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 13px; font-weight: 600; ">Name : <?php echo $advanec->Employee_Name; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 13px; font-weight: 600; ">Phone No. : <?php echo $advanec->Employee_ContactNo; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12">
                <table class="border" cellspacing="0" cellpadding="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="font-size: 14px; font-weight: 700;text-align:center;">Sl No</th>
                            <th style="font-size: 14px; font-weight: 700;text-align:center;">Description</th>
                            <th style="font-size: 14px; font-weight: 700;text-align:center;">Month</th>
                            <th style="font-size: 14px; font-weight: 700;text-align:center;">Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: center;">01</td>
                            <td><?php echo $advanec->note; ?></td>
                            <td><?php echo $advanec->month_name; ?></td>
                            <td style="text-align:right;"><?php echo number_format($advanec->amount, 2); ?></td>
                        </tr>
                        <tr>
                            <th colspan="3" style="font-size: 13px; text-align: right;">Total Amount:</th>
                            <th style="font-size: 13px; font-weight: 700;text-align:right;"><?php echo number_format($advanec->amount, 2);?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12">
                <h6 style=" font-size: 12px; font-weight: 600;">Paid (In Word): <?php echo convertNumberToWord($advanec->amount);?></h6>
            </div>
        </div>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12 text-left;">
                <table style="width: 25%;float:left;">
                </table>
                <div style="float:right;text-decoration: overline;">
                    <strong>Autorizied signature</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 

  function convertNumberToWord($num = false){
      $num = str_replace(array(',', ' '), '' , trim($num));
      if(! $num) {
          return false;
      }
      $num = (int) $num;
      $words = array();
      $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
          'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
      );
      $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
      $list3 = array('', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
          'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
          'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
      );
      $num_length = strlen($num);
      $levels = (int) (($num_length + 2) / 3);
      $max_length = $levels * 3;
      $num = substr('00' . $num, -$max_length);
      $num_levels = str_split($num, 3);
      for ($i = 0; $i < count($num_levels); $i++) {
          $levels--;
          $hundreds = (int) ($num_levels[$i] / 100);
          $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '');
          $tens = (int) ($num_levels[$i] % 100);
          $singles = '';
          if ( $tens < 20 ) {
              $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
          } else {
              $tens = (int)($tens / 10);
              $tens = ' ' . $list2[$tens] . ' ';
              $singles = (int) ($num_levels[$i] % 10);
              $singles = ' ' . $list1[$singles] . ' ';
          }
          $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
      } //end for loop
      $commas = count($words);
      if ($commas > 1) {
          $commas = $commas - 1;
      }
      $inword = implode(' ', $words) ."Taka Only";
    return strtoupper($inword);
  }
  
?>

<script>
    let printIcon = document.querySelector('#printIcon');
    printIcon.addEventListener('click', () => {
        event.preventDefault();
        print();
    })
    async function print(){
        let reportContent = `
            <div class="container">
                ${document.querySelector('#reportContent').innerHTML}
            </div>
        `;

        var reportWindow = window.open('', 'PRINT', `height=${screen.height}, width=${screen.width}`);
        reportWindow.document.write(`
            <?php $this->load->view('Administrator/reports/reportHeader.php');?>
        `);

        reportWindow.document.head.innerHTML += `<link href="<?php echo base_url()?>assets/css/prints.css" rel="stylesheet" />`;
        reportWindow.document.body.innerHTML += reportContent;

        reportWindow.focus();
        await new Promise(resolve => setTimeout(resolve, 1000));
        reportWindow.print();
        reportWindow.close();
    }
</script>