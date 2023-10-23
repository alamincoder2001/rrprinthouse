<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report</title>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap.min.css">
    <style>
        body{
            padding: 20px!important;
        }
        body, table{
            font-size: 13px;
        }
        table th{
            text-align: center;
        }
    </style>
</head>
<body>
    <?php 
        $branchId = $this->session->userdata('BRANCHid');
        $companyInfo = $this->Billing_model->company_branch_profile($branchId);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <img src="<?php echo base_url();?>uploads/rr-invoice-top.png" alt="Logo" style="width: 100%" />
            </div>
        </div>
    </div>
</body>
</html>