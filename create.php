
    <?php
        session_start();
        require_once("src/database.php");
        require_once("src/utils.php");
        if(isset($_SESSION['user']) && intval($_SESSION['user']['active']))
            include_once("parts/createForm.php");
        else {

            ?>
            <div class="content">
                <div class="anounce error">
                    You need to be logged in as an active user to use this page
                </div>
            </div>            
            <?php
        }   
    ?>
