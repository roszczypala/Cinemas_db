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
        <h1>Wszystkie kina:</h1>      
            <?php
            require_once 'connection.php';
            $sql = "SELECT * FROM Cinemas ORDER BY Cinemas.name";
            $result = $conn->query($sql);

            if($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<h3>'. $row['name'] . '</h3>';

                    $sqlShows = "SELECT * FROM Seans JOIN Movies ON Seans.movies_id = Movies.id
                                WHERE cinemas_id = {$row['id']} ORDER BY Movies.title";
                    $resultShows = $conn->query($sqlShows);
                    if($resultShows->num_rows > 0) {
                        echo '<ul>';
                        while($rowShow = $resultShows->fetch_assoc()) {
                            echo '<li><b>' . $rowShow['title'] .'</b> '. $rowShow['date'] .' <b>Time:</b> '. $rowShow['time'] . '</li>';
                        }
                        echo '</ul>';
                    }else{
                        echo 'Brak seansów';
                    }
                }
            }else{
                echo 'Brak kin<br>';
            }
            ?>
        </fieldset>          
     </body>
 </html>

 
