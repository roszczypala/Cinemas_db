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
        <h1>Wszystkie filmy:</h1>      
        </div>
        <div>
        <div>
        <?php
        require_once 'connection.php';
        $sql = "SELECT * FROM Movies ORDER BY Movies.title";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<h3>'. $row['title'] . '</h3>';

                $sqlCinemas = "SELECT * FROM Seans JOIN Cinemas ON Seans.cinemas_id = Cinemas.id
                               WHERE movies_id = {$row['id']} ORDER BY Cinemas.name";
                $resultCinemas = $conn->query($sqlCinemas);
                if($resultCinemas->num_rows > 0) {
                    echo '<ul>';
                    while($rowCinema = $resultCinemas->fetch_assoc()) {
                        echo '<li><b>' . $rowCinema['name'] .'</b> Adres: '. $rowCinema['address'] . '</li>';
                    }
                    echo '</ul>';
                }else{
                    echo 'Żadne kino nie wyświetla tego filmu';
                }
            }
        }else{
            echo 'Brak filmu<br>';
        }
        ?>
        </div>
        </div>
        </div>
        </fieldset>
    </body>
</html>

