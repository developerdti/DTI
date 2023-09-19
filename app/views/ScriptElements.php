        <script src="<?php echo DEPENDENCE_PATH?>/@popperjs/core/dist/umd/popper.min.js"></script>

        <script src="<?php echo DEPENDENCE_PATH?>/bootstrap/dist/js/bootstrap.min.js"></script>
        
        <script src="<?php echo DEPENDENCE_PATH?>/jquery/dist/jquery.min.js"></script>

        <script src="<?php echo DEPENDENCE_PATH?>/jquery-tabledit/jquery.tabledit.min.js"></script>

        <script  src="<?php echo DEPENDENCE_PATH?>/chart.js/dist/chart.umd.js"></script>
        
        <?php if(isset($filejs) && file_exists(PROJECT_PATH.'/app/assets/js/'.$filejs.'.js')){
            echo '<script src="'.SCRIPT_PATH.'/'.$filejs.'.js"></script>';
        }?>

    </body>
</html>