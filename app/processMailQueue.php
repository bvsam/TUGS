<?php
require("database.php");

require_once ($_SERVER['DOCUMENT_ROOT'] . "/emmell/mail/mailer.php");
$sendingPermission = "tugsA-debug";

$query = $pdo->prepare("SELECT * FROM mailQueue");
$query->execute();
$results = $query->fetchAll(PDO::FETCH_ASSOC);

for ($i = 0;$i < count($results);$i++)
{
    if (sendEmail($sendingPermission,  $results[$i]['mail_fromName'], $results[$i]['mail_toName'], $results[$i]['mail_toAddr'], $results[$i]['mail_subj'], $results[$i]['mail_body']))
    {
        $query = $pdo->prepare("DELETE FROM mailQueue WHERE mail_id = :id_placeholder");
        $query->execute(array(
            "id_placeholder" => $results[$i]['mail_id']
        ));
        echo "done";
    }
}
?>
