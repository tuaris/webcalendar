<?php
/* $Id$ */
include_once 'includes/init.php';
// .
/*
 Month and year are being overwritten so we will copy vars to fix.
 This will make datesel.php still work from where ever it is called.
 The values $fday, $fmonth and $fyear hold the form variable names
 to update when the user selects a date.  (This is needed in the
 js/datesel.php file that gets included below.)
*/
$fday = getGetValue ( 'fday' );
$fmonth = getGetValue ( 'fmonth' );
$fyear = getGetValue ( 'fyear' );

$form = getGetValue ( 'form' );

if ( strlen ( $date ) > 0 ) {
  $thisyear = substr ( $date, 0, 4 );
  $thismonth = substr ( $date, 4, 2 );
} else {
  $thisyear = date ( 'Y' );
  $thismonth = date ( 'm' );
}

$next = mktime ( 0, 0, 0, $thismonth + 1, 1, $thisyear );
$nextdate = date ( 'Ym', $next ) . '01"';
$nextmonth = date ( 'm', $next );
$nextyear = date ( 'Y', $next );

$prev = mktime ( 0, 0, 0, $thismonth - 1, 1, $thisyear );
$prevdate = date ( 'Ym', $prev ) . '01"';
$prevmonth = date ( 'm', $prev );
$prevyear = date ( 'Y', $prev );

$monthStr = month_name ( $thismonth - 1 );
$href = 'href="datesel.php?form=' . $form . '&amp;fday=' . $fday
 . '&amp;fmonth=' . $fmonth . '&amp;fyear=' . $fyear . '&amp;date=';

print_header ( array ( 'js/datesel.php/false/' . "$form/$fmonth/$fday/$fyear" ),
  '', '', true, false, true );

echo <<<EOT
    <div align="center">
      <table class="aligncenter" width="100%" height="165px">
        <tr>
          <td align="center" valign="middle">
            <table class="aligncenter">
              <tr>
                <td><a title="{$translations['Previous']}" class="prev" {$href}
                  {$prevdate}><img src="images/leftarrowsmall.gif"
                     alt="{$translations['Previous']}" /></a></td>
                <th colspan="5">&nbsp;{$monthStr}&nbsp;{$thisyear}&nbsp;</th>
                <td><a title="{$translations['Next']}"class="next" {$href}
                  {$nextdate}><img src="images/rightarrowsmall.gif"
                     alt="{$translations['Next']}" /></a></td>
              </tr>
              <tr class="day">
EOT;

if ( $WEEK_START == 0 )
  echo '
                <td>' . weekday_name ( 0, 'D' ) . '</td>';
for ( $i = 1; $i < 7; $i++ ) {
  echo '
                <td>' . weekday_name ( $i, 'D' ) . '</td>';
}
echo ( $WEEK_START == 1 ? '
                <td>' . weekday_name ( 0, 'D' ) . '</td>' : '' ) . '
              </tr>';
$wkstart = get_weekday_before ( $thisyear, $thismonth );

$monthstart = mktime ( 0, 0, 0, $thismonth, 1, $thisyear );
$monthend = mktime ( 23, 59, 59, $thismonth + 1, 0, $thisyear );
for ( $i = $wkstart; date ( 'Ymd', $i ) <= date ( 'Ymd', $monthend );
  $i += ( 86400 * 7 ) ) {
  echo '
              <tr>';
  for ( $j = 0; $j < 7; $j++ ) {
    $date = $i + ( $j * 86400 ) + 43200;
    echo '
                <td'
     . ( ( date ( 'Ymd', $date ) >= date ( 'Ymd', $monthstart ) &&
        date ( 'Ymd', $date ) <= date ( 'Ymd', $monthend ) ) ||
      $DISPLAY_ALL_DAYS_IN_MONTH == 'Y'
      ? ' class="field"><a href="javascript:sendDate (\''
       . date ( 'Ymd', $date ) . '\')">' . date ( 'j', $date ) . '</a>'
      : '>' ) . '</td>';
  }
  echo '
              </tr>';
}

echo '
            </table>
          </tr>
        </td>
      </table>
    </div>
    '. print_trailer ( false, true, true );

?>
