<?php
    /*     * ***********************************************
      APIError.php

      Displays error parameters.

      Called by DoDirectPaymentReceipt.php, TransactionDetails.php,
      GetExpressCheckoutDetails.php and DoExpressCheckoutPayment.php.

     * *********************************************** */

    $resArray = $_SESSION['reshash'];
?>
<div class="container_bg" id="View_Pages">
    <div class="Box">
        <div class="Box_Head">
            <h2><?php echo translate("Payment Canceled!"); ?></h2>
        </div>
        <div class="Box_Content">
            <table width="280">
                <tr>
                    <td colspan="2" class="header">The PayPal API has returned an error!</td>
                </tr>

                <?php
                    //it will print if any URL errors 
                    if (isset($_SESSION['curl_error_no']))
                    {
                        $errorCode = $_SESSION['curl_error_no'];
                        $errorMessage = $_SESSION['curl_error_msg'];
                        session_unset();
                        ?>


                        <tr>
                            <td>Error Number:</td>
                            <td><?php echo $errorCode; ?></td>
                        </tr>
                        <tr>
                            <td>Error Message:</td>
                            <td><?php echo $errorMessage; ?></td>
                        </tr>

                        </center>
                    </table>
                <?php
                }
                else
                {

                    /* If there is no URL Errors, Construct the HTML page with 
                      Response Error parameters.
                     */
                    ?>

                    <center>
                        <font size=2 color=black face=Verdana><b></b></font>
                        <br><br>

                        <b> PayPal API Error</b><br><br>

                        <table width="400">
                            <?php
                            foreach ($resArray as $key => $value)
                            {
                                echo "<tr><td> $key:</td><td>$value</td>";
                            }
                            ?>
                        </table>
                    </center>		

        <?php
    }// end else
?>
            </center>
            </table>
        </div>

    </div>
</div>