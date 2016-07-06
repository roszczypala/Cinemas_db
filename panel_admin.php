<!DOCTYPE html>
<?php
require_once 'connection.php';
?>
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
        <h1>Panel Administracyjny</h1>
        </fieldset> 
        <fieldset>     
            <?php
                if($_SERVER['REQUEST_METHOD'] === 'POST'){

                switch($_POST['submit']){

                    case 'cinema':
                        $name = isset($_POST['name']) && strlen($_POST['name']) > 0 ?$_POST['name'] :null;
                        $address = isset($_POST['address']) && strlen($_POST['address']) > 0 ?$_POST['address'] :null;

                        if($name && $address) {
                            $sql = "INSERT INTO Cinemas (name, address) VALUES ('$name', '$address')";

                            if($conn->query($sql)){
                                echo("Dodano nowe kino");
                            }  
                        }
                        break;

                    case 'film':
                        $title = isset($_POST['title']) && strlen($_POST['title']) > 0 ?$_POST['title'] :null;
                        $description = isset($_POST['description']) && strlen($_POST['description']) > 0 ?$_POST['description'] :null;
                        $rating = isset($_POST['rating']) && $_POST['rating'] >= 0.0 && $_POST['rating'] <= 10.0?$_POST['rating'] :null;

                        if($title && $description && $rating) {
                            $sql = "INSERT INTO Movies (title, description, rating) VALUES ('$title', '$description', '$rating')"; 

                            if($conn->query($sql)) {
                                echo("Dodano nowy film");
                            }
                        }
                        break;

                    case 'show':
                        if(isset($_POST['cinema']) && isset($_POST['movie']) && isset($_POST['date']) && isset($_POST['time'])) {

                            $sql = "INSERT INTO Seans (cinemas_id, movies_id, date, time) VALUES ('{$_POST['cinema']}', '{$_POST['movie']}', '{$_POST['date']}', '{$_POST['time']}')";
                            if($conn->query($sql)) {
                                echo("Added new show");
                            }else{
                                echo("Error adding new show");
                            }
                        }else{
                            echo("Connection error");
                        }
                        break;  
                        
                    case 'allFilms':
                    if( isset($_GET['table']) && isset($_GET['idToDelete'])) {
                            $table = $conn->escape_string($_GET['table']);
                            $idToDelete = $conn->escape_string($_GET['idToDelete']);
                            $query = "DELETE FROM $table WHERE id=$idToDelete";
                            if($conn->query($query)) {
                                    echo "usunieto $table";
                            } else {
                                    echo "Wystąpił błąd podczas usuwania z $table";
                            }
                    }

                    //wszystkie filmy
                    $query = "SELECT * FROM Movies";
                    $result = $conn->query($query);
                    if($result->num_rows > 0) {
                            echo '<table>';
                            echo '<tr><th>id</th><th>title</th></tr>';
                            while($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>'.$row['id'].'</td>';
                                    echo '<td>'.$row['title'].'</td>';
                                    echo "<td><a href='panel_admin.php?table=Movies&idToDelete={$row['id']}'>delete</a></td>";
                                    echo '</tr>';
                            }
                            echo '</table>';
                    }
                    break;
                        
                    case 'allCinemas':
                        if( isset($_GET['table']) && isset($_GET['idToDelete'])) {
                            $table = $conn->escape_string($_GET['table']);
                            $idToDelete = $conn->escape_string($_GET['idToDelete']);
                            $query = "DELETE FROM $table WHERE id=$idToDelete";
                            if($conn->query($query)) {
                                    echo "usunieto $table";
                            } else {
                                    echo "Wystąpił błąd podczas usuwania z $table";
                            }
                    }

                    //wszystkie filmy
                    $query = "SELECT * FROM Cinemas";
                    $result = $conn->query($query);
                    if($result->num_rows > 0) {
                            echo '<table>';
                            echo '<tr><th>id</th><th>title</th></tr>';
                            while($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>'.$row['id'].'</td>';
                                    echo '<td>'.$row['name'].'</td>';
                                    echo "<td><a href='panel_admin.php?table=Cinemas&idToDelete={$row['id']}'>delete</a></td>";
                                    echo '</tr>';
                            }
                            echo '</table>';
                    }
                    break;
                         
                }
            }   
            ?>
        
            <hr>
            <div>
                <div>
                    <h2>Panel "SEANS"</h2>
                    
                    <form method="POST">
                        <div>
                            <label>Nazwa kina<br>
                                <select name="cinema">
                                    <?php                    
                                    $sql = "SELECT * FROM Cinemas";
                                    $result = $conn->query($sql);                   
                                    if($result->num_rows > 0) {                        
                                        while($row = $result->fetch_assoc()) {                            
                                            echo("<option value = '{$row['id']}'> {$row['name']} </option>");                            
                                        }
                                    }
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div>
                            <label>Tytuł filmu<br>
                                <select name="movie">
                                    <?php
                                    $sql = "SELECT * FROM Movies";
                                    $result = $conn->query($sql);                   
                                    if($result->num_rows > 0) {  
                                        while($row = $result->fetch_assoc()) {    
                                            echo("<option value = '{$row['id']}'> {$row['title']} </option>");    
                                        }
                                    }
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div>
                            <label>Data wyświetlania filmu
                                <input type="date" name="date">
                            </label>
                        </div>
                        <div>
                            <label>Godzina wyświetlania filmu
                                <input type="time" name="time">
                            </label>
                        </div>
                        <button type="submit" name="submit" value="show">Dodaj seans</button>

                    </form>
                </div>
                <hr>
                <div>
                    <form method="post" action="#">
                        <h2>Panel "KINO"</h2>
                        <div>
                            <label>Nazwa</label>
                            <input name="name" type="text" maxlength="255" value=""/>
                        </div>
                        <div>
                            <label>Adres</label>
                            <input name="address" type="text" maxlength="255" value=""/>
                        </div>
                        <button type="submit" name="submit" value="cinema">Dodaj kino</button>
                        <button type="submit" name="submit" value="allCinemas" >Wyświetl wszystkie kina z opcją usunięcia wybranego</button>
                    </form>
                </div> 
                <hr>
                <div>
                    <form method="post" action="#">
                        <h2>Panel "FILM"</h2>
                        <div>
                            <label>Tytuł</label>
                            <input name="title"  type="text" maxlength="255" value=""/>
                        </div>
                        <div>
                            <label>Opis</label>
                            <input name="description"  type="text" maxlength="255" value=""/>
                        </div>
                        <div>
                            <label>Ocena</label>
                            <input name="rating" type="number" min="0.0" step="0.1" max="10.0"/>
                        </div>
                        <button type="submit" name="submit" value="film">Dodaj film</button>
                        <button type="submit" name="submit" value="allFilms" >Wyświetl wszystkie filmy z opcją usunięcia wybranego</button>
                    </form>
                </div>
            </div> 
            <hr>
            <div>
            <div>
                <h2>Panel "PŁATNOŚCI"</h2>
                <form method="POST">
                    <div>
                    <button type="submit" name="submit" value="transfer">Płatności przelewem</button>
                    <button type="submit" name="submit" value="cash">Płatności gotówkowe</button>
                    <button type="submit" name="submit" value="card">Płatności kartą</button>
                    <button type="submit" name="submit" value="none">Nieopłacone bilety</button>
                    <button type="submit" name="submit" value="all">Wyświetl wszystkie płatności</button>
                    </div>
                </form>
            <hr>
                <?php
                if($_SERVER['REQUEST_METHOD'] === 'POST') {

                    switch($_POST['submit']) {

                        case "transfer":
                            $sql = "SELECT Tickets.* FROM Tickets JOIN Payments ON Tickets.id=Payments.id WHERE type='transfer'";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo("Ilość: " . $row['quantity'] . " Cena: ". $row['price'] . "<br>");
                                }
                            }
                            else {
                                echo("Brak biletów");
                            }
                            break;
                        case "cash":
                            $sql = "SELECT Tickets.* FROM Tickets JOIN Payments ON Tickets.id=Payments.id WHERE type='cash'";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo("Ilość: " . $row['quantity'] . " Cena: ". $row['price'] . "<br>");
                                }
                            }
                            else {
                                echo("Brak biletów");
                            }
                            break;
                        case "card":
                            $sql = "SELECT Tickets.* FROM Tickets JOIN Payments ON Tickets.id=Payments.id WHERE type='card'";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo("Ilość: " . $row['quantity'] . " Cena: ". $row['price'] . "<br>");
                                }
                            }
                            else {
                                echo("Brak biletów");
                            }
                            break;
                        case "none":
                            $sql = "SELECT Tickets.* FROM Tickets LEFT JOIN Payments ON Tickets.id=Payments.id WHERE type IS NULL";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo("Ilość: " . $row['quantity'] . " Cena: ". $row['price'] . "<br>");
                                }
                            }else{
                                echo("Brak biletów");
                            }
                            break;

                    case "all":
                            $sql = "SELECT Tickets.* FROM Tickets LEFT JOIN Payments ON Tickets.id=Payments.id";
                            $result = $conn->query($sql);
                            if($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo("Ilość: " . $row['quantity'] . " Cena: ". $row['price'] . "<br>");
                                }
                            }else{
                                echo("Brak biletów");
                            }
                            break;
                    }
                }
                ?>
             </div>
         </div>
         </fieldset> 
     </body>
 </html>