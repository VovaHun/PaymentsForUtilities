<div class = "header">
    
    <div class = "container head">
        
        <div class = "logo">
            
            <a href = "/ManagementCompany/">
                <img src = "/images/logo.png" alt = "">
            </a>
            
        </div>
        
        <div class = "telephone">
            
            <a href = "tel:8652991000">(8652) 991-000</a>
            
        </div>
        
        <div class = "free_space"></div>
        
        <?php 
            if ( isset ($_SESSION['logged_company_admin']) )
            {
                ?>
                    
                    <div class = "reg">
                        Здравствуйте, <?php echo $company_admin['Name']; ?>
                    </div>
                    
                    <div class = "auth">
                        <a href = "/ManagementCompany/logout.php">Выйти</a>
                    </div>
                    
                <?php
            }
        ?>
        
    </div>
    
</div>