<?php 
session_start();
require_once("Classes/DB.php");

if(!is_file('Classes/build_done')){
    $data = file_get_contents('Classes/build.sql');
    $ret = Database::build($data);
    touch('Classes/build_done');

}

function is_valid_action($action){
    $actions = [ 
        'update',
        'delete'
    ];
    if(in_array($action, $actions)){
        return True;
    }else{
        return False;
    }
}

$CalenderPrices = [
    'mainPrice'=>35,
    'discount'=>30
];

if(isset($_GET['url'])){
    $page_query = explode('/', $_GET['url']);
}else{
    $page_query = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calender</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js" defer></script>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php 
        //if you would like to add authentication to the app uncomment the following line
        //require('Classes/auth.php');
    ?>
    <div class="header">
        <h2>2022 Calender</h2>
    </div>
    <?php
        

        if(isset($_SESSION['Notify'])){
            if(isset($_SESSION['Notify_error']) and $_SESSION['Notify_error'] == 1){
                $color = ' error"';
            }elseif(isset($_SESSION['Notify_error']) and $_SESSION['Notify_error'] == 0){
                $color = ' sucess"';
            }
            echo '<div class="notify '.$color.'">'.$_SESSION['Notify'].' </div>';
            unset($_SESSION['Notify']);
            unset($_SESSION['Notify_error']);
        }
    ?>
    
    <div class="content">
        <?php
            if(!empty($page_query) and is_file('Views/'.$page_query[0].'.php')){
                include('Views/'.$page_query[0].'.php');
            }else{
                $DB = new Database;

                
                echo '<h3>Purchases</h3>';
                $total_data = $DB->query('SELECT Number, Calenders.Name From Purchases inner join Calenders on Purchases.Calender = Calenders.ID');
                $totals = [];
                foreach($total_data as $calenderTypes){
                     $totals[$calenderTypes['Name']] += $calenderTypes['Number'];   
                }
                echo '<div class="stat"><span>Total</span><br>'.array_sum($totals).'</div>';
                foreach($totals as $k=>$ou){
                    echo '<div class="stat"><span>'.$k.'</span><br>'.$ou.'</div>';
                }
            }
        ?>
        
    </div>
    <div class="menu">
            <a href="/order" class="btn blue darken-3 menu-wide"><img src="/img/order.png" class="menu-icon"></a> 
            <a href="/customer" class="btn blue darken-3 menu-wide" ><img src="/img/customer.png" class="menu-icon"></a> 
            <a href="/calender" class="btn blue darken-3 menu-wide"><img src="/img/calender.png" class="menu-icon"></a>
            <a href="/purchase" class="btn blue darken-3 menu-wide"><img src="/img/purchase.png" class="menu-icon"></a>
        </div>
    
    <script src="/js/menu.js"></script>
    <script src="/js/main.js"></script>
</body>
</html>


