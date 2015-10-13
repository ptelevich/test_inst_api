<form method="get">
    <input type="hidden" name="c" value="<?= $options['c'] ?>" />
    <input type="hidden" name="a" value="<?= $options['a'] ?>" />
    <input type="hidden" name="code" value="<?= $options['code'] ?>" />
    <h1>Filters:</h1>
    <table border="0" cellpadding="0" align="center">
        <tr>
            <td>@userID</td>
            <td>@username</td>
            <td>Date From</td>
            <td>Date To</td>
            <td></td>
        </tr>
        <tr>
            <td><input type="text" name="userId" value="<?= !empty($_GET['userId']) ? $_GET['userId']: '' ?>" /></td>
            <td><input type="text" name="userName" value="<?= !empty($_GET['userName']) ? $_GET['userName'] : '' ?>" /></td>
            <td><input type="text" name="dateFrom" value="<?= !empty($_GET['dateFrom']) ? $_GET['dateFrom'] : '' ?>" class="dateRange" /></td>
            <td><input type="text" name="dateTo" value="<?= !empty($_GET['dateTo']) ? $_GET['dateTo'] : '' ?>" class="dateRange" /></td>
            <td><input type="submit" value="Search" /></td>
        </tr>
    </table>
</form>
