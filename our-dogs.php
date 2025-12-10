<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/db_connect.php');

// Sanitize and validate the 'pet' ID
$ID = filter_input(INPUT_GET, 'pet', FILTER_VALIDATE_INT);

$dogs = [];

if ($ID !== null && $ID !== false) {
    $query_dogs = "SELECT * FROM Dog_Listings WHERE ID = :ID";
    $dog_statement = $db->prepare($query_dogs);
    $dog_statement->bindValue(':ID', $ID, PDO::PARAM_INT);
    $dog_statement->execute();
    $dogs = $dog_statement->fetchAll();
    $dog_statement->closeCursor();
}
?>
<!DOCTYPE html>
<?php $PageName = "Our Dogs"; ?>
<html lang="en">
<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/modules/head.php"); ?>
</head>
<body data-pagename="Our Dogs">
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/modules/header.php"); ?>
    <main>
        <section class="our-dogs content">
            <div class="wrapper">
                <?php foreach ($dogs as $dog): ?>	
                    <header>
                        <h1><?php echo htmlspecialchars($dog['Name']); ?></h1>
                        <h2><?php echo htmlspecialchars($dog['Headline']); ?></h2>
                    </header>		
                    <a href="/edit/?edit=<?php echo (int)$dog['ID']; ?>" class="btn">Edit & View</a>
                    <div class="featured-pet" data-id="<?php echo (int)$dog['ID']; ?>">
                        <span class="photo">
                            <img src="/browser/images/<?php echo htmlspecialchars($dog['Photo']); ?>" alt="<?php echo htmlspecialchars($dog['Name']); ?>">
                        </span>
                        <p><?php echo nl2br(htmlspecialchars($dog['Bio'])); ?></p>
                        <a href="/adoption-application/<?php echo $ID ? '?pet=' . $ID : ''; ?>" class="btn btn-orange">Go to Adoption Application</a>
                    </div>
                <?php endforeach; ?>

                <iframe 
                    style="width:725px;height:400px;max-width:100%" 
                    src="https://www.youtube.com/embed/ounP-yYcxC0" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        </section>

        <?php include($_SERVER['DOCUMENT_ROOT'] . "/modules/dog-listings.php"); ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/modules/ways-to-help.php"); ?>
    </main>

    <footer>
        <?php include($_SERVER['DOCUMENT_ROOT'] . "/modules/footer.php"); ?>
    </footer>
</body>
</html>
