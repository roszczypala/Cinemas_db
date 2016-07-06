<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8">
         <title></title>
     </head>
     <body>
        <fieldset> 
        <a href="index.php">Wróć na stronę główną</a>
        </fieldset>  
        <fieldset>
        <div>
            <div>
                <div>
                    <h1>Kup bilet</h1>      
                </div>
            </div>
            <div>
                <div>
                    <form method="POST">
                        <div>
                           <label>Seanse
                                <select name="seans">    
                                    <?php
                                    require_once 'connection.php';
                                    $sql = "SELECT Cinemas.name, Movies.title, Seans.id, Seans.date, Seans.time FROM Seans
                                    JOIN Movies ON Seans.movies_id = Movies.id
                                    JOIN Cinemas ON Seans.cinemas_id = Cinemas.id";
                                    $result = $conn->query($sql);
                                    if($result->num_rows > 0) {
                                       while ($row = $result->fetch_assoc()) {
                                            echo "<option value = '{$row['id']}'> {$row['name']}: {$row['title']}  Date: {$row['date']}  Time: {$row['time']}</option>";
                                        }
                                    }else{
                                        echo 'Brak <br>';
                                    }
                                    ?>
                                </select>
                            </label>    
                        </div>
                        <div>    
                            <label>Ilość biletów
                            <input name="quantity" type="number" min="0"/>
                            </label>
                        </div>
                        <div> 
                            <label>Cena
                                <select name="price">
                                    <option value="12">12 PLN </option>
                                    <option value="15">15 PLN </option>
                                    <option value="18">18 PLN </option>
                               </select>
                            </label>    
                        </div>
                        <div> 
                            <label>Typ płatności
                                <select name="type">
                                    <option value="none">Zapłać później</option>
                                    <option value="transfer">Przelew</option>
                                    <option value="cash">Gotówka</option>
                                    <option value="card">Karta</option>
                                </select>
                            </label>
                        </div>
                        <button type="submit" name="submit" value="seansTicket" >Kup</button>
                    </form>
                </div>
            <div>
                <?php
                    if($_SERVER['REQUEST_METHOD'] === 'POST')  {

                        if($_POST['quantity'] > 0 && in_array($_POST['price'], ['12', '15','18']) && in_array($_POST['type'], ['transfer', 'cash','card','none'])) {
                            $count = $_POST['quantity'];
                            $price = $_POST['price'];
                            $type = $_POST['type'];
                            $seans = $_POST['seans'];

                            $sql = "INSERT INTO Tickets (quantity, price, seans_id) VALUES ('$count', '$price', '$seans')";

                            if($conn->query($sql)) {
                                echo("Dodano bilet<br>");
                                $last_id = $conn->insert_id;

                                if(in_array($type, ['transfer', 'cash','card'])) {

                                    $sqlPayment = "INSERT INTO Payments (id, type, date) VALUES ('$last_id', '$type', NOW())";
                                    if($conn->query($sqlPayment)) {
                                        echo("Dodano płatność<br>");
                                    }else{
                                        echo("Brak płatności<br>");
                                    }
                                }else{
                                    echo("nie dodano płatności<br>");
                                }
                            }else{
                                echo("Błąd nie dodano biletu<br>");
                            }
                        }
                        $sqlTicketInfo = "SELECT Cinemas.name, Movies.title, Seans.id, Tickets.price, Tickets.quantity FROM Seans
                                    JOIN Movies ON Seans.movies_id =Movies.id
                                    JOIN Cinemas ON Seans.cinemas_id = Cinemas.id
                                    JOIN Tickets ON Seans.id = Tickets.seans_id
                                    WHERE Tickets.id = '$last_id'";

                        $resultTicket = $conn->query($sqlTicketInfo); 
                        if($resultTicket->num_rows > 0) {                        
                                while($row = $resultTicket->fetch_assoc()) { 
                                    $cost = $row['quantity'] * $row['price'];
                                    echo("Kupiono {$row['quantity']} bilet/y na film: {$row['title']} grany w: {$row['name']}<br>");    
                                    echo("Całkowita suma: $cost PLN"); 
                                }
                        }    
                    }
                ?>
                </div>
            </div>
        </div> 
        </fieldset> 
     </body>
 </html>

