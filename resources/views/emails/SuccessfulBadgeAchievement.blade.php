<!DOCTYPE html>
<html>
<head>
    <title>Challenge In Motion</title>
</head>


<body>
    <p>Congratulations {{$userName}},</p>
    <p>You’ve earned the {{$badgeName}} Badge.</p>
    <p>Your new badge is in the trophy section on the Challenge In Motion platform. To view your trophy case, click on the link below:</p>
   
    <p>Link: <a href="{{$badgeLink}}" style="color:blue;">{{$badgeLink}}</a></p>
     </br>
    <p>Regards,</p>
    <p>Challenge In Motion</p>
</body>
</html>