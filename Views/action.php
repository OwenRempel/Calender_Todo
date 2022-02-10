<?php
    if(isset($page_query[1]) and !empty($page_query[1])){
        echo "<a href='/order/' class='btn blue darken-3'>Back</a>";
    }else{
        echo "<a href='/' class='btn blue darken-3'>Back</a>";
    }
?>
<h3>Deliver</h3>
<?php
class Action{
    public static function deliver($ID){
        if(isset($_GET['back'])){
            if($_GET['back'] > 5){
                $num = $_GET['back'] - 5;
                $back = '/order#n'.$num;
            }else{
                $back = '/order';
            }
            
        }else{
            $back = '/order';
        }
        $DB = new Database;
        if(isset($_POST['deliver'])){
            $dataSend = $DB->query('UPDATE Orders SET Delivered=:number WHERE ID=:id', array(
                'number'=>$_POST['Number'],
                'id'=>$_POST['ID']
            ));
            $_SESSION['Notify'] = 'Delivered '.$_POST['Number'].' Calenders';
            $_SESSION['Notify_error'] = 0;
            header('Location:'.$back);
        }
        $data = $DB->query('SELECT Number, Delivered, ID FROM Orders WHERE ID=:id', array('id'=>$ID));
        if(isset($data[0])){
            ?>
                <form action="" method="post">
                    <h5 class="deleteBack">Chose how many to Deliver<br><br> Max:<?php echo $data[0]['Number'];?></h5>
                    <div class="input-field col s6">
                        <input type="number" name="Number" max='<?php echo $data[0]['Number'];?>' value='<?php echo $data[0]['Number'];?>' >
                        <label for="Number">Number</label>
                    </div>
                    <input type="hidden" name="ID" value='<?php echo $data[0]['ID'];?>'>
                    <button class="btn blue darken-3" type="submit" name="deliver" value='1'>Submit
                </button>
                </form>
            <?php   
        }else{
            $_SESSION['Notify'] = 'Enter Valid ID';
            $_SESSION['Notify_error'] = 1;
            header('Location:'.$back);
        }
    }
    public static function paid($ID){
        if(isset($_GET['back'])){
            if($_GET['back'] > 5){
                $num = $_GET['back'] - 5;
                $back = '/order#n'.$num;
            }else{
                $back = '/order';
            }
            
        }else{
            $back = '/order';
        }
        $DB = new Database;
        if(isset($_POST['paid'])){
            $dataSend = $DB->query('UPDATE Orders SET Paid=:number WHERE ID=:id', array(
                'number'=>$_POST['Number'],
                'id'=>$_POST['ID']
            ));
            $_SESSION['Notify'] = 'Paid '.$_POST['Number'].' Calenders';
            $_SESSION['Notify_error'] = 0;
            header('Location:'.$back);
        }
        $data = $DB->query('SELECT Number, Paid, ID FROM Orders WHERE ID=:id', array('id'=>$ID));
        if(isset($data[0])){
            ?>
                <form action="" method="post">
                    <h5 class="deleteBack">Chose how many Were Paid<br><br> Max:<?php echo $data[0]['Number'];?></h5>
                    <div class="input-field col s6">
                        <input type="number" name="Number" max='<?php echo $data[0]['Number'];?>' value='<?php echo $data[0]['Number'];?>' >
                        <label for="Number">Number</label>
                    </div>
                    <input type="hidden" name="ID" value='<?php echo $data[0]['ID'];?>'>
                    <button class="btn blue darken-3" type="submit" name="paid" value='1'>Submit
                </button>
                </form>
            <?php   
        }else{
            $_SESSION['Notify'] = 'Enter Valid ID';
            $_SESSION['Notify_error'] = 1;
            header('Location:'.$back);
        }
    }
    public static function deliverAll($ID){
        if(isset($_GET['back'])){
            if($_GET['back'] > 5){
                $num = $_GET['back'] - 5;
                $back = '/customer#n'.$num;
            }else{
                $back = '/customer';
            }
            
        }else{
            $back = '/customer';
        }
        $DB = new Database;
        if(isset($_POST['deliverAll'])){
            $orderList = $DB->query('SELECT Number, ID FROM Orders WHERE Customer=:cust',array('cust'=>$_POST['ID']));
            foreach($orderList as $row){
                $dataSend = $DB->query('UPDATE Orders SET Delivered=:number WHERE ID=:id', array(
                    'number'=>$row['Number'],
                    'id'=>$row['ID']
                ));
            }
            
            $_SESSION['Notify'] = 'Delivered all Calenders to Customer';
            $_SESSION['Notify_error'] = 0;
            header('Location:'.$back);
        }
        $data = $DB->query('SELECT ID FROM Orders WHERE ID=:id', array('id'=>$ID));
        if(isset($data[0])){
            ?>
                <form action="" method="post">
                    <h5 class="deleteBack">Mark all as Delivered</h5>
                    <input type="hidden" name="ID" value='<?php echo $data[0]['ID'];?>'>
                    <button class="btn blue darken-3" type="submit" name="deliverAll" value='1'>Submit
                </button>
                </form>
            <?php   
        }else{
            $_SESSION['Notify'] = 'Enter Valid ID';
            $_SESSION['Notify_error'] = 1;
            header('Location:/'.$back);
        }
    }
    public static function paidAll($ID){
        if(isset($_GET['back'])){
            if($_GET['back'] > 5){
                $num = $_GET['back'] - 5;
                $back = '/customer#n'.$num;
            }else{
                $back = '/customer';
            }
            
        }else{
            $back = '/customer';
        }
        $DB = new Database;
        if(isset($_POST['deliverAll'])){
            $orderList = $DB->query('SELECT Number, ID FROM Orders WHERE Customer=:cust',array('cust'=>$_POST['ID']));
            foreach($orderList as $row){
                $dataSend = $DB->query('UPDATE Orders SET Paid=:number WHERE ID=:id', array(
                    'number'=>$row['Number'],
                    'id'=>$row['ID']
                ));
            }
            
            $_SESSION['Notify'] = 'All Calenders Paid';
            $_SESSION['Notify_error'] = 0;
            header('Location:'.$back);
        }
        $data = $DB->query('SELECT  ID  FROM Orders WHERE ID=:id', array('id'=>$ID));
        if(isset($data[0])){
            ?>
                <form action="" method="post">
                    <h5 class="deleteBack">Mark all as Paid</h5>
                    <input type="hidden" name="ID" value='<?php echo $data[0]['ID'];?>'>
                    <button class="btn blue darken-3" type="submit" name="deliverAll" value='1'>Submit
                </button>
                </form>
            <?php   
        }else{
            $_SESSION['Notify'] = 'Enter Valid ID';
            $_SESSION['Notify_error'] = 1;
            header('Location:'.$back);
        }
    }
}

$actions = new Action;
if(isset($page_query[1]) and !empty($page_query[1]) and !empty($page_query[2]) and $page_query[1] == 'deliver'){
    $actions->deliver($page_query[2]);
}elseif(isset($page_query[1]) and !empty($page_query[1]) and !empty($page_query[2]) and $page_query[1] == 'paid'){
    $actions->paid($page_query[2]);
}elseif(isset($page_query[1]) and !empty($page_query[1]) and !empty($page_query[2]) and $page_query[1] == 'deliverAll'){
    $actions->deliverAll($page_query[2]);
}elseif(isset($page_query[1]) and !empty($page_query[1]) and !empty($page_query[2]) and $page_query[1] == 'paidAll'){
    $actions->paidAll($page_query[2]);
}else{
            $_SESSION['Notify'] = 'Enter Valid ID';
            $_SESSION['Notify_error'] = 1;
            header('Location:/order');
}