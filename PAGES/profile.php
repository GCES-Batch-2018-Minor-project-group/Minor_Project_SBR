<?php

    include("../PHP/includes/encryption.php");

    if(!isset($_GET['Logged'])){
        header("location: ../index.php?NotLogged");
    }

    require "../PHP/includes/connection.php";
    include("../PHP/includes/table_columns_name.php");

    session_start();

    $uId = $_SESSION['uId'];

    $sql_info = "SELECT users.$citizenship_column, users.$image_column, users.$username_column, users.$phoneNumber_column, vehicles_data.* FROM users JOIN vehicles_data ON users.uId=vehicles_data.uId WHERE users.uId='$uId' && vehicles_data.uId='$uId'";
    $info_result = mysqli_query($connect, $sql_info);

    $infoarray = mysqli_fetch_all($info_result, MYSQLI_ASSOC);

    $str = "/6G6F;WvK7;s{au/6G6F;WvK7;s{au";
    $key = md5($str);

    $sql_form = "SELECT * FROM forms_data WHERE $formFillerId_column='$uId';";
    $form_result = mysqli_query($connect, $sql_form);

    $sql_tax="SELECT * FROM tax_details WHERE $fillerId_column ='$uId';";
    $tax_result=mysqli_query($connect,$sql_tax);

    if($infoarray[0][$image_column] != null){
        $img = $infoarray[0][$image_column];
        $image_src = "../ASSETS/upload/" . $img;
    }

    $formArray = mysqli_fetch_all($form_result,MYSQLI_ASSOC);

    $taxArray = mysqli_fetch_all($tax_result,MYSQLI_ASSOC);
    $vcat = $infoarray[0][$vehicleCategory_column];
    $enginecc = $infoarray[0][$engineCC_column];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <link rel="stylesheet" href="../CSS/search.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/profile.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    
    <style>
        #closeBtn-holder{
            display:block;
            text-align:right;
        }
        
        #close-mark{
            text-align:right;
            font-size:40px;
            color:white;
        }
        #close-mark,.fa-edit{
            cursor:pointer;
        }

    </style>
    <script defer src="../JS/detail_popup.js"></script>
    <script defer src="../JS/category_type.js"></script>
    <script defer src="../JS/profile.js"></script>
</head>
<body class="col-12">
    <header class="col-12">

        <!-- logo and website name -->
        <span id="logo">SWIFT BLUEBOOK RENEW</span>

        <!-- navigation bar -->
        <nav>
            <ul>
                <li><a href="./information.php?isLogged=true">Infos</a></li>
                <li><a href="./form.php">Form</a></li>
                <li><a href="#" class="active-nav-link">Profile</a></li>
                <li><a href="./taxCalculator.php">Tax Calculator</a></li>
            </ul>
        </nav>

    </header>

   <div class="bg-overlay"></div>
   
    <div class="detail-popup">
            <div id="closeBtn-holder">
            <span id="close-mark" onclick="closedEditPopup()">
            &times;
            </span>
    </div>
    <form action="../PHP/handleBasicdata.php" method="POST">
                <table>
                    <tr>
                        <td>
                            <label for="type">Vehicle Type</label>
                        </td>
                        <td>
                        <select name="vType" id="type"  required>
                                <option value="none"></option>
                                <option value="two wheeler" <?php if($infoarray[0][$vehicleType_column] == "two wheeler"){ echo "selected";} ?>>Two wheeler</option>
                                <option value="four wheeler" <?php if($infoarray[0][$vehicleType_column] == "four wheeler"){ echo "selected";} ?>>Four wheeler</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="category">Vehicle Category</label>
                        </td>
                        <td><select name="vCategory" id="category" required>
                            <?php 
                                echo "<option value='$vcat'>$vcat</option>";
                            ?>
                        </select></td>
                    </tr>
                    <tr>
                        <td>
                            <label for="engCC">Engine cc</label>
                        </td>
                        <td>
                            <select name="ECC" id="engCC" required>
                                    <?php
                                            echo "<option value='$enginecc'>$enginecc</option>";
                                    ?>
                            </select>
                        </td>
                    </tr>
                   <tr>
                    <td>
                        <label for="pp">Private or Public</label>
                    </td>
                    <td>
                        <input type="radio" name="pORp" id="pp" value="private" <?php if($infoarray[0][$pp_column] == "private"){echo "checked";}else{echo "disabled";} ?>/>Private
                        <input type="radio" name="pORp" id="pp" value="public" <?php if($infoarray[0][$pp_column] == "public"){echo "checked";}else{echo "disabled";} ?>/>Public
                    </td>
                   </tr>
                    <tr>
                        <td>
                            <label for="registration-no">Vehicle Registration Number </label>
                        </td>
                        <td><input type="text" name="regNo" id="registration-no" placeholder="GA 16 PA 4381" value="<?=decryptData($infoarray[0][$vehicleRegistration_column],$key); ?>"></td>
                    </tr>
                   <tr>
                       <td>
                            <label for="phn">Phone number</label>
                       </td>
                       <td>
                            <input type="text" name="Phn" id="phn" value="<?=decryptData($infoarray[0][$phoneNumber_column],$key); ?>">
                       </td>
                   </tr>
                    <tr>
                        <td > 
                      </td>
                        <td class="btn-td" colspan=2>
                            <input type="submit" name="savebtn" class="editsavebtn" value="SAVE">
                        </td>
                    </tr>
                </table>
            </form>
    </div>
    <main class="col-12">
    <div class="profile-wrapper">
        <div class="profile-side-section">
            <div id="user-name">
                <h4>
                    <?php
                       echo decryptData($infoarray[0][$username_column],$key);
                    ?>
                </h4>
            </div>

            <div class="profile-photo-wrapper">
                <div id="add-photo" >
                    <div class="profile-image">
                        <?php
                            if($infoarray[0][$image_column] == null){
                                echo "";
                            }else{
                                echo "<img src='$image_src' alt='profile picture' id='profile_picture'>";
                            }
                        ?>
                    </div>
                    <div class="image-adder-btn">
                        <div>
                            <form action='../PHP/handleImage.php' method='POST' enctype='multipart/form-data'>
                                <input type='file' name='img' onchange='this.form.submit();' id='image_adder'>
                                <label for='image_adder'>
                                    <i class='far fa-image'> </i> 
                                    <span class="tooltip" id="add-image">Upload image</span> 
                                <lable>
                            </form>
                        </div>
                        <div>
                            <form action="../PHP/deleteImage.php" id="delete-image-form" method="POST">
                                <label for="image-remover" onclick="submitForm()">
                                    <i class="fas fa-user-times"></i>
                                    <span class="tooltip" id="delete-image">Delete image</span>
                                </label>
                            <input type="hidden" value="delete" name="delete-btn">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-btn-wrapper">
                <button class="btn basic-btn active-btn">Basic Details</button>
                <button class="btn tax-btn">Tax Details</button>
                <button class="btn latest-form-btn">Latest Form Detail</button>
            </div>
            <div class="logout-btn-holder">
                <button > <a href="../PHP/logout.php" class="non-nav-link">Log Out</a> </button>
            </div>
        </div>

        <div class="profile-detail-section basic-detail">
        
            <div class="detail-form">
            <h3><u> DETAILS</u></h3>
                <table>
                    <tr>
                        <td>Phone number :</td>
                        <td> <?php 
                        
                        if(empty($infoarray)){
                            echo "???";
                        }else{
                            echo decryptData($infoarray[0][$phoneNumber_column],$key);
                        }
                        
                        ?> </td>
                    </tr>
                    <tr>
                        <td>Citizenship number :</td>
                        <td> 
                            <?php
                                if(empty($infoarray)){
                                    echo "???";
                                }else{
                                    echo decryptData($infoarray[0][$citizenship_column],$key);
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Vehicle Type:</td>
                        <td> 
                            <?php    
                                if(empty($infoarray) || $infoarray[0][$vehicleType_column] == ""){
                                    echo "???";
                                }else{
                                    echo $infoarray[0][$vehicleType_column];
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Vehicle Category :</td>
                        <td>
                            <?php
                                if(empty($infoarray) || $infoarray[0][$vehicleCategory_column] == ""){
                                    echo "???";
                                }else{
                                    echo $infoarray[0][$vehicleCategory_column];
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>ENGINE_CC :</td>
                        <td> 
                            <?php
                                 if(empty($infoarray) || $infoarray[0][$engineCC_column] == ""){
                                    echo "???";
                                }else{
                                    echo $infoarray[0][$engineCC_column];
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="pp">Private or Public</label>
                        </td>
                        <td>
                            <input type="radio" name="pORp" id="pp" value="private" <?php if($infoarray[0][$pp_column] == "private"){echo "checked";}else{echo "disabled";} ?>/>Private
                            <input type="radio" name="pORp" id="pp" value="public"  <?php if($infoarray[0][$pp_column] == "public"){echo "checked";}else{echo "disabled";} ?>/>Public
                        </td>
                   </tr>
                    <tr>
                        <td>Vehicle Registration no. :</td>
                        <td> 
                            <?php
                                 if(empty($infoarray) || $infoarray[0][$vehicleRegistration_column] == ""){
                                    echo "???";
                                }else{
                                    echo decryptData($infoarray[0][$vehicleRegistration_column],$key);
                                }
                            ?>
                       </td>
                    </tr>
                </table>
          <div class="editBtn">
          <i class="fa fa-edit" onclick="openEditPopup()">Edit</i>
          </div>
        </div>           
        </div>

        <div class="profile-detail-section tax-detail">
            <div class="detail-form">
                <h3><u>Tax Detail</u></h3>
                <table>
                    <tr>
                        <th>YEAR</th>
                        <th>FINE</th>
                        <th>TAX AMOUNT</th>
                    </tr>
                <?php 
                if(sizeof($taxArray) < 3){
                    foreach($taxArray as $data) {?>
                        <tr>
                            <td>
                                <?= $data[$createdAt_column]?>
                            </td>
                            <td>
                                <?= $data[$fineAmount_column]?>
                            </td>
                            <td>
                                <?= $data[$taxAmount_column]?>
                            </td>
                        </tr>
                
                    <?php }
                }else{
                    for($i= sizeof($taxArray)-1; $i > sizeof($taxArray)-4;$i--){ ?>
                        <tr>
                            <td>
                                <?= $taxArray[$i][$createdAt_column]?>
                            </td>
                            <td>
                                <?= $taxArray[$i][$fineAmount_column]?>
                            </td>
                            <td>
                                <?= $taxArray[$i][$taxAmount_column]?>
                            </td>
                        </tr>
                
                    <?php }  } ?>
                </table>
                <div class="slider-wrapper editBtn">
                    <span>&lt;</span>
                    <span>&gt;</span>
                </div>
            </div>
        </div>

        <div class="profile-detail-section form-detail">
            <div class="detail-form">
                <h3><u>Latest Form</u></h3>
                <div class="form-template">
                
                </div>
            </div>
        </div>


        <!-- <div id="more-details" class="maindiv">
            <h3><u> MORE DETAILS</u></h3>
            <div id="latest-form">
                <h4>Latest Form</h4>
                <?php foreach($formArray as $data) { ?>
                    <table class="form-table">
                        <tr>
                            <td>Name :</td>
                            <td> <?=$data['NAME']?></td>
                            <td>Phone number :</td>
                            <td> <?=$data['PHN']?></td>
                        </tr>
                        <tr>
                            <td>Vehicle Type :</td>
                            <td> <?=$data['VEHICLE_TYPE']?></td>
                            <td>Vehicle Category :</td>
                            <td>  <?=$data['VEHICLE_CAT']?></td>
                        </tr>
                        <tr>
                            <td> Engine CC :</td>
                            <td> <?=$data['ENGINE_CC']?></td>
                            <td> Vehicle Registration no. :</td>
                            <td> <?=$data['VEHICLE_REG']?></td>
                        </tr>
                        <tr>
                            <td colspan="2">Last Renew Date</td>
                            <td colspan="2"><?=$data['RENEW_DATE']?></td>
                        </tr>
                    </table>
                <?php } ?>
            </div>
        </div> -->
        </div>
    </main> 
     
    <?php include '../repeated_section/footer.html' ?>
    <script>
        function submitForm(){
            document.getElementById('delete-image-form').submit();
        }
    </script>
 
</body>
</html>