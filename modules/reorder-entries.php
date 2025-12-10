<?php
if (isset($_POST['Form_Name']) && $_POST['Form_Name'] == 'Reorder_Entries') {
    if (isset($_POST['Order'])) {

        // Decode the JSON order data
        $Order = json_decode($_POST['Order']);

        if ($Order && isset($Order->ordering)) {
            // Prepare the query
            $query = '';
            foreach ($Order->ordering as $entry) {
                // Ensure we only receive valid integers
                $id = filter_var($entry[0], FILTER_VALIDATE_INT);
                $sequence = filter_var($entry[1], FILTER_VALIDATE_INT);

                if ($id && $sequence) {
                    $query .= "UPDATE Dog_Listings SET Sequence = :sequence WHERE ID = :id; ";
                }
            }

            if (!empty($query)) {
                require_once $_SERVER['DOCUMENT_ROOT'] . '/db_connect.php';
                $statement = $db->prepare($query);

                // Bind the values for each query
                foreach ($Order->ordering as $entry) {
                    $id = filter_var($entry[0], FILTER_VALIDATE_INT);
                    $sequence = filter_var($entry[1], FILTER_VALIDATE_INT);

                    if ($id && $sequence) {
                        $statement->bindValue(':id', $id, PDO::PARAM_INT);
                        $statement->bindValue(':sequence', $sequence, PDO::PARAM_INT);
                    }
                }

                // Execute the query
                $execute_success = $statement->execute();
                $statement->closeCursor();

                if (!$execute_success) {
                    error_log("Error updating reorder entries: " . implode(", ", $statement->errorInfo()));
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title></title>
</head>
<body>
</body>
</html>