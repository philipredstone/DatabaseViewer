<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Database Viewer</title>
    <link href="./stylenew.css" rel="stylesheet">
</head>
<body>
    <main>
        <div class="wrapper-card">
            <h2 style="font-weight: 900;">Log In</h2>
            <h3>Please Log In to view the databases!</h3>
            <form action="index.php" method="post">
                <div class="text-field text-field--outlined full-width" style="max-width: 90%; left: 5%;">
                    <input type="text" id="username-field" name="user" class="text-field__input" required>
                    <div class="notched-outline notched-outline--upgraded notched-outline--notched">
                        <div class="notched-outline__leading"></div>
                        <div class="notched-outline__notch" style="width: 64px;">
                            <label class="floating-label floating-label--float-above" for="my-text-field">Username</label>
                        </div>
                        <div class="notched-outline__trailing"></div>
                    </div>
                </div>  
                <div class="text-field text-field--outlined full-width" style="max-width: 90%; left: 5%;">
                    <input type="password" id="password-field" name="pass" class="text-field__input" required>
                    <div class="notched-outline notched-outline--upgraded notched-outline--notched">
                        <div class="notched-outline__leading"></div>
                        <div class="notched-outline__notch" style="width: 62.25px;">
                            <label class="floating-label floating-label--float-above" for="my-text-field">Password</label>
                        </div>
                        <div class="notched-outline__trailing"></div>
                    </div>
                </div> 
                <input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
            </form>       
            <div class="footer-wrapper" style="position: fixed; right: 5%; bottom: 5%;">
            </div>
         </div>
    </main>
  </body>
</html>