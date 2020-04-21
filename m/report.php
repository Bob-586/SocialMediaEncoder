<?php

$reasons = [
    ''        => "Select Abuse, if valid only:",
 'fake_goods' => 'Sale or Promotion of COUNTERFEIT/PROHIBITED goods', 
    'privacy' => 'Privacy/Doxing Policy: Address, Email, or Phone number given', 
    'violent' => 'Violent Threats with Intent to do harm',
    'slander' => 'Slander/Defamation of Reputation/Malicious Falsehood of person/company'
];

/**
   * Purpose: To output all HTML select (drop down) option values.
   * @param array $options array of key=>value
   * @param string $default Item to select on.
   * @param string $select_by ['value'] for most of your needs.
   * @param array $a as optional options
   * @retval string of option values...
   */
  function do_options(array $options, string $default = '', string $select_by = 'text', array $a = array()): string {
    $more = '';
    if (count($a)) {
      foreach ($a as $k => $v) {
        $more .= " {$k}=\"{$v}\"";
      }
    }

    $values = '';
    foreach ($options as $value => $text) {
      $compair_to = ($select_by == 'text') ? $text : $value;
      $selected = (!empty($default) && $default == $compair_to) ? 'selected' : '';
      $values .= "<option value=\"{$value}\" " . $selected . " " . $more . ">{$text}</option>";
    }
    return $values;
  }
  ?>

<div id="ReportModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Report Abuse</h2>
    </div>
    <div class="modal-body">
       <select onchange="report(this);" id="reporting">
        <?= do_options($reasons); ?>
      </select>
    </div>
    <div class="modal-footer">
      <h3>Please only report, seriously wrong, violations!</h3>
    </div>  
  </div>
</div>

<script type="text/javascript">
var report_id = 0;
function report(me) {
    var v = me.options[me.selectedIndex].text;
    if (confirm("Are you absolutly sure of " + v + " was a valid violation on them?")) {
    
        if (me.value !== "" && report_id > 0) {
            postAjax('do_report.php', { id: report_id, flag: me.value }, function(json) {
                var obj = JSON.parse(json);
                if (obj.hasOwnProperty('Failed')) {
                    alert(obj.Failed);
                } else if (obj.hasOwnProperty('Error')) {
                    alert(obj.Error);
                } else if (obj.hasOwnProperty('Success')) {
                    alert(obj.Success);
                }
            });
        }
    
    }
    modal.style.display = "none";
    document.getElementById("reporting").selectedIndex = 0;
}
function btn_report(id) {
    report_id = id;
    modal.style.display = "block";
}
/* Get the modal */
var modal = document.getElementById("ReportModal");
/* Get the <span> element that closes the modal */
var span = document.getElementsByClassName("close")[0];
/* When the user clicks on <span> (x), close the modal */
span.onclick = function() {
  modal.style.display = "none";
}
/* When the user clicks anywhere outside of the modal, close it */
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
