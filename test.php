
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

    <form action="" method="POST">
        <div>
            <div>
                <input type="text" id="inp">
            </div>
        </div>
    </form>

    <script type="text/javascript" defer>
        let target = $("#inp").parent();
        while(target.prop("tagName") != "FORM") {
            target = target.parent();
        }

        console.log(target);
    </script>

</body>
</html>