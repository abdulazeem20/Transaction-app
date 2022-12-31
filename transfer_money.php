<?php
require_once 'public/header.php';
$page = 'transfer';

if (!isset($_SESSION['loggedIn']) && !isset($_SESSION['user_id'])) {
    header('location: login.php');
}
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $userDetails = $obj_Auth->getUserDetails($user_id);
    //getting user balance
    $myBal = $obj_Auth->getBalance($user_id);
}
$match = "/^[\d,.]*[\d]+$/i";
$id = $err = $success = $recipentId = $newBal = $accountNumber = $recipentAccNumber = $save = '';
if (isset($_POST['submit'])) {
    $accountNumber = $_POST['accountNumber'];
    $userId = $userDetails['id'];
    $amount = $_POST['amount'];
    $recipentId = $_POST['recipent_id'];
    if (empty($amount)) {
        $err = 'fill in the amount';
    } elseif (!(preg_match_all($match, $amount))) {
        $err = 'Kindly input a valid amount';
    } else {
        // Saving into TRANSFER TABLE
        $transfer = $obj_Auth->saveIntoTransfer($userId, $recipentId, $amount);
        if ($transfer) {
            $success = 'Transaction sucessful';
        }

        //Saving into TRANSACTION TABLE to keep track of the debit of the current user
        $transact_type = 'Debit';
        $obj_Auth->saveIntoTransaction($_SESSION['user_id'], $transact_type, $recipentId, $amount, 1);

        //geting the balance of the receiver
        $receiverBalance = $obj_Auth->getBalance($recipentId);
        //adding the transfered amount to the receiver amount
        $receiverAmount = $receiverBalance + $amount;

        // Updating the balance of the receiver
        $obj_Auth->updateBalance($recipentId, $receiverAmount);

        //Updating BALANCE for the curent user
        $userBal = $obj_Auth->getBalance($userId); // Getting user current balance before this transaction
        $newBal = $userBal - $amount;
        $obj_Auth->updateBalance($_SESSION['user_id'], $newBal);

        //updating users balance
        $myBal = $obj_Auth->getBalance($user_id);
    }
}

?>
<script>
document.title = "<?= $userDetails['firstname']; ?> - Transfer Money";
</script>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once 'public/include/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">



            <!-- Topbar -->
            <?php require_once 'public/include/navbar.php'; ?>
            <!-- End of Topbar -->

            <div class="col-xl-6 col-md-9 mb-4 ml-5 justify-content-center">
                <div class="card border-left-primary shadow ">
                    <div class="card-body m-5  ">

                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form">
                            <?php if ($err) {?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $err; ?>!!.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php } elseif ($success) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $success; ?>!!.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php } ?>
                            <div class="form-group row ">
                                <label class="col-md-4" for="">Search Recipent </label>
                                <div class="col-md-8">
                                    <input type="text" name="accountNumber" class="form-control" id="account_suggest"
                                        value="<?php echo $accountNumber; ?>" placeholder="Search by account no or name" autocomplete="off">
                                    <div id="acc_details">

                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style="display: none"  >
                                <label class="col-md-4" for=""> Recipent id </label>
                                <div class="col-md-8">
                                    <input type="hidden" name="recipent_id" class="form-control" id="recipent_id">
                                </div>
                            </div>

                            <div class="form-group row" style="display: none"   id="recipent_div">
                                <label class="col-md-4" for="">Recipent Account No </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="recipent_name">                                    
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label class="col-md-4 " for="">Amount</label>
                                <div class="col-md-8">
                                    <input type="text" name="amount" id="amount" class="form-control">
                                    
                                </div>

                            </div>

                            <div class="form-group row ">
                                <div class="offset-md-4 col-md-5">
                                   <p><strong>Bal.: </strong> <b style="font-size: 17px; color: green" id="balance" oninput="this.value = this.value.replace(/[^0-9\.]+/g, '').replace(/(\..*)\./g, '$1');" ><?php echo number_format($myBal, 2); ?></b></p>
                                   <input type="hidden" id="curr_bal" value="<?php echo $myBal; ?>">
                                   
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" name="submit" class="btn btn-primary"
                                        style="width: 100%;">Transfer</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php

        require_once 'public/footer.php';

    ?>
    <script>
    $(document).ready(function() {
        
        // $('#amount').keyup(function() {
        //     var amount = $(this).val();
        //     var expression = /^[\d,.]*[\d]+$/gi;
        //     if (!(amount.match(expression))) {
        //         var error = "*Kindly Input a valid amount";
        //         $('#error').html(error);
        //     } else if (amount.match(expression)) {
        //         $('#error').html('');
        //     }
        // })

        // var ss = "<li>Nigeria My Country</li><li>Nigeria My Country</li>"

        $('#account_suggest').keyup(function() {
            var search = $(this).val()
            if(search != '') {
                $.ajax({
                    method: "GET",
                    contentType: false,
                    data: {
                        "search": search
                    },
                    url: 'get_acc_details.php',
                    dataType: "Json",
                    success: function(response) {
                        var ul = '<ul class="list-group">'
                        $.each(response, function(i, value) {
                            name = value.firstname+ " "+ value.othername;
                            ul += '<li class="list-group-item list-group-item-action" data-value="'+value.account_no+'" value ='+ value.id +' >'+ name + '</li>';
                        })
                        ul += "</ul>"
                        $('#acc_details').show();
                        $('#acc_details').html(ul);
                        console.log(id);
                        //
                    } 
                })
            }
        })


        //$('#selectUser').click(function(){
        $(document).on('click', 'li', function(){
            var list = $(this).text();
            //Get recipent ID
            getRecipentId($(this).val())

            getRecipentAccount($(this).data('value'))
            // console.log("Clicked: "+list)
            $('#account_suggest').val(list)
            $('#acc_details').hide();

            
        })
        function getRecipentId(recipentId){
            $('#recipent_id').val(recipentId);
        }

        function getRecipentAccount(acc_num) {
            $('#recipent_name').val(acc_num)
            $('#recipent_div').show();
        }

        $(document).on('keyup', '#amount', function(){
            var amount = $('#amount').val();  //what you type
            amount = parseFloat(amount);
            var balance = $('#curr_bal').val() //current balance
            balance = parseFloat(balance)

            if($('#amount').val() == ''){
                amount = 0
            }

            if(amount > balance) {
                $('#amount').val(balance)
                amount = parseFloat($('#amount').val());
            }

            var remainder = balance - amount

            // console.log("Amount: "+amount+" Bal: "+balance+ " Rem: "+remainder)

            $('#balance').text(remainder)

            
            

            
        })
    })
    </script>