<?php
if (isset($_GET['tid'])) {
    $typeId = intval($_GET['tid']);
    if ($typeId == 1) { // Single Date
        echo '<div class="form-group row mb-3">
                <div class="col-xl-6">
                    <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                    <input type="date" class="form-control" name="singleDate" required>
                </div>
              </div>';
    } elseif ($typeId == 2) { // Date Range
        echo '<div class="form-group row mb-3">
                <div class="col-xl-6">
                    <label class="form-control-label">From Date<span class="text-danger ml-2">*</span></label>
                    <input type="date" class="form-control" name="fromDate" required>
                </div>
                <div class="col-xl-6">
                    <label class="form-control-label">To Date<span class="text-danger ml-2">*</span></label>
                    <input type="date" class="form-control" name="toDate" required>
                </div>
              </div>';
    }
}
?>
