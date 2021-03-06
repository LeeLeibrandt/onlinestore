<?php

    require_once "config.php";

    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
    
        // Validate username
        if(empty(trim($_POST["username"])))
        {
            $username_err = "Please enter username.";
        }
         else
        {
            // Prepare a select statement
            $sql = "SELECT id FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($link, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                
                // Set parameters
                $param_username = trim($_POST["username"]);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1)
                    {
                        $username_err = "This username is already taken.";
                    } 
                    else
                    {
                        $username = trim($_POST["username"]);
                    }
                } 
                else
                {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        
        // Validate password
        if(empty(trim($_POST["password"])))
        {
            $password_err = "Please enter a password.";     
        } 
        elseif(strlen(trim($_POST["password"])) < 6)
        {
            $password_err = "Password must have atleast 6 characters.";
        } 
        else
        {
            $password = trim($_POST["password"]);
        }
        
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"])))
        {
            $confirm_password_err = "Please confirm password.";     
        } 
        else
        {
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password))
            {
                $confirm_password_err = "Password did not match.";
            }
        }
        
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
        {
            
            // Prepare an insert statement
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql))
            {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                
                // Set parameters
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT); 
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt))
                {
                    header("location: login.php");
                } 
                else
                {
                    echo "Error.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_close($link);
    }
    include_once('header.php');
?>

    <div class="login-area login-s2">
        <div class="container">
            <div class="login-box ptb--100">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2>Sign Up</h2>
                    <p>Please fill this form to create an account.</p>
                    <div class="login-form-body">
                        <div class="form-gp <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                            <i class="ti-user"></i>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" placeholder="Username">
                            <span class="help-block"><?php echo $username_err; ?></span>
                            <div class="text-danger"></div>
                        </div>    
                        <div class="form-gp <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <i class="ti-lock"></i>
                            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" placeholder="Password">
                            <span class="help-block"><?php echo $password_err; ?></span>
                            <div class="text-danger"></div>
                        </div>
                        <div class="form-gp <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                            <i class="ti-lock"></i>
                            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password">
                            <span class="help-block"><?php echo $confirm_password_err; ?></span>
                            <div class="text-danger"></div>
                        </div>
                        <div class="submit-btn-area">
                            <button type="submit" class="btn btn-primary">SUBMIT</button>
                        </div>
                    
                        <p>Already have an account? <a href="login.php">Login here</a>.</p>
                    </div>
                </form>
            </div>  
        </div>  
    </div>
<?
    include_once('footer.php');
?>