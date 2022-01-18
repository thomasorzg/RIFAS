<?php
    require("../dbengine/dbconnect.php");
    function formdate($dateString) 
    {
        $myDateTime = DateTime::createFromFormat('Y-m-d', $dateString);
        $newDateString = $myDateTime->format('d-m-Y');
        return $newDateString;
    }
    if ($_GET['type'] == 'get_tickets') {
        $id = $_GET['raffle_id'];

        $query = mysqli_query($conn, "SELECT ticket_code FROM raffle_tickets WHERE raffle_id = $id AND ticket_status=0");
        
        $response = [];
        if(($query) && (mysqli_num_rows($query) > 0)){
            while($row=mysqli_fetch_assoc($query)) {
                $response[] = $row;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    if ($_GET['type'] == 'get_raffle') {
        $id = $_GET['raffle_id'];

        $query = mysqli_query($conn, "SELECT * FROM raffles WHERE raffle_id = $id");
        
        $response = [];
        if(($query) && (mysqli_num_rows($query) > 0)){
            while($row=mysqli_fetch_assoc($query)) {
                $response = $row;
                $response['raffle_price'] = $response['raffle_price']/100;
                $response['raffle_date'] = formdate($response['raffle_date']);
                $response['raffle_end'] = formdate($response['raffle_end']);
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    if ($_GET['type'] == 'get_payment') {
        $id = $_GET['payment_id'];

        $query = mysqli_query($conn, "SELECT * FROM payments WHERE payment_id = $id");
        
        $response = [];
        if(($query) && (mysqli_num_rows($query) > 0)){
            while($row=mysqli_fetch_assoc($query)) {
                $response = $row;
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
?>