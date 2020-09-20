<div class = "header">
    
    <div class = "container head">
        
        <div class = "logo">
            
            <a href = "/AdminPart/">
                <img src = "/images/logo.png" alt = "">
            </a>
            
        </div>
        
        <div class = "telephone">
            
            <a href = "tel:8652991000">(8652) 991-000</a>
            
        </div>
        
        <div class = "free_space"></div>
        
        <?php 
            if ( isset ($_SESSION['logged_main_admin']) )
            {
                ?>
                    
                    <div class = "reg">
                        Здравствуйте, <?php echo $main_admin['Name']; ?>
                    </div>
                    
                    <div class = "auth">
                        <a href = "/GlobalAdmins/logout.php">Выйти</a>
                    </div>
                    
                <?php
            }
        ?>
        
    </div>
    
</div>