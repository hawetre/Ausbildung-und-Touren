<?php
    echo "PW-Hash Mitgliedsnummer mit Check";
    var_dump(password_hash('123/00/123456', PASSWORD_BCRYPT));
    echo "<br>";
    #var_dump(password_verify('123/00/123456', '$2y$10$yUcqAdLcOkXGmbtDf2CR8.bJIhOSu03bF4vGshyUy.A6NkgqSYGx2'));
    echo "<br>";
    #var_dump(password_hash('108/00/095442', PASSWORD_BCRYPT));