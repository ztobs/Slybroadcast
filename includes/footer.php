<?php
/**
 * Created by PhpStorm.
 * User: Joseph Lukan
 * Date: 7/29/2017
 * Time: 3:04 PM
 */

?>

<div class="footer-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="copyright">
                    © 2017
                </div>
            </div>
            <div class="col-md-4 col-md-offset-3">
                <div class="design">
                    Built by <a target="_blank" href="http://tobilukan.com">Donztobs</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery Version 1.11.1 -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Font Awesome -->
<script src="https://use.fontawesome.com/334109738a.js"></script>

<!-- Custom Scripts  -->
<?php
    foreach ($page_footer_scripts as $script)
    {
?>
        <script src="<?php echo $script; ?>"></script>
<?php
    }
?>
<script src="js/script.js"></script>
</body>

</html>
