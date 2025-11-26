<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Create New Opportunity</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('sales/opportunities') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('sales/opportunities/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary">Basic Information</h5>

                    <div class="mb-3">
                        <label for="project_name" class="form-label">Project Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="project_name" name="project_name"
                            value="<?= old('project_name') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="account_id" class="form-label">Account <span class="text-danger">*</span></label>
                        <select class="form-select" id="account_id" name="account_id" required>
                            <option value="">Select Account</option>
                            <?php foreach ($accounts as $account): ?>
                                <option value="<?= $account['id'] ?>" <?= old('account_id') == $account['id'] ? 'selected' : '' ?>>
                                    <?= esc($account['account_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="principal_id" class="form-label">Principal <span class="text-danger">*</span></label>
                        <select class="form-select" id="principal_id" name="principal_id" required>
                            <option value="">Select Principal</option>
                            <?php foreach ($principals as $principal): ?>
                                <option value="<?= $principal['id'] ?>" <?= old('principal_id') == $principal['id'] ? 'selected' : '' ?>>
                                    <?= esc($principal['principal_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="pic" class="form-label">PIC (Person In Charge)</label>
                        <input type="text" class="form-control" id="pic" name="pic"
                            value="<?= old('pic') ?>" placeholder="Name - Position">
                    </div>
                </div>

                <!-- Technical & Commercial -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary">Technical & Commercial</h5>

                    <div class="mb-3">
                        <label for="solution_architect_id" class="form-label">Solution Architect</label>
                        <select class="form-select" id="solution_architect_id" name="solution_architect_id">
                            <option value="">Select Solution Architect</option>
                            <?php foreach ($solution_architects as $sa): ?>
                                <option value="<?= $sa['id'] ?>" <?= old('solution_architect_id') == $sa['id'] ? 'selected' : '' ?>>
                                    <?= esc($sa['sa_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="license_type_id" class="form-label">License Type</label>
                        <select class="form-select" id="license_type_id" name="license_type_id">
                            <option value="">Select License Type</option>
                            <?php foreach ($license_types as $license): ?>
                                <option value="<?= $license['id'] ?>" <?= old('license_type_id') == $license['id'] ? 'selected' : '' ?>>
                                    <?= esc($license['license_type']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="services_license" class="form-label">Services & License Details</label>
                        <textarea class="form-control" id="services_license" name="services_license"
                            rows="3" placeholder="Describe the services and licenses required..."><?= old('services_license') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="estimated_value" class="form-label">Estimated Value (IDR) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="estimated_value" name="estimated_value"
                            value="<?= old('estimated_value') ?>" step="0.01" min="0" required>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <!-- Progress & Status -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary">Progress & Status</h5>

                    <div class="mb-3">
                        <label for="opportunity_status" class="form-label">Opportunity Status</label>
                        <select class="form-select" id="opportunity_status" name="opportunity_status">
                            <option value="introduction" <?= old('opportunity_status') == 'introduction' ? 'selected' : '' ?>>Introduction</option>
                            <option value="qualification" <?= old('opportunity_status') == 'qualification' ? 'selected' : '' ?>>Qualification</option>
                            <option value="proposal" <?= old('opportunity_status') == 'proposal' ? 'selected' : '' ?>>Proposal</option>
                            <option value="negotiation" <?= old('opportunity_status') == 'negotiation' ? 'selected' : '' ?>>Negotiation</option>
                            <option value="closed_won" <?= old('opportunity_status') == 'closed_won' ? 'selected' : '' ?>>Closed Won</option>
                            <option value="closed_lost" <?= old('opportunity_status') == 'closed_lost' ? 'selected' : '' ?>>Closed Lost</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="progress_percentage" class="form-label">Progress Percentage</label>
                        <input type="range" class="form-range" id="progress_percentage" name="progress_percentage"
                            min="0" max="100" value="<?= old('progress_percentage') ?? 0 ?>"
                            oninput="updateProgressValue(this.value)">
                        <div class="d-flex justify-content-between">
                            <small>0%</small>
                            <small id="progressValue"><?= old('progress_percentage') ?? 0 ?>%</small>
                            <small>100%</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="progress" class="form-label">Progress Description</label>
                        <input type="text" class="form-control" id="progress" name="progress"
                            value="<?= old('progress') ?>" placeholder="e.g., Proposal Sent, Technical Demo Completed...">
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary">Additional Information</h5>

                    <div class="mb-3">
                        <label for="deal_reg" class="form-label">Deal Registration Number</label>
                        <input type="text" class="form-control" id="deal_reg" name="deal_reg"
                            value="<?= old('deal_reg') ?>" placeholder="e.g., DR-2024-001">
                    </div>

                    <div class="mb-3">
                        <label for="closing_date" class="form-label">Expected Closing Date</label>
                        <input type="date" class="form-control" id="closing_date" name="closing_date"
                            value="<?= old('closing_date') ?>">
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks"
                            rows="3" placeholder="Additional notes or comments..."><?= old('remarks') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Success Message -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success mt-3">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- Form Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('sales/opportunities') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Opportunity</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function updateProgressValue(value) {
        document.getElementById('progressValue').textContent = value + '%';
    }

    // Auto-format currency input
    document.getElementById('estimated_value').addEventListener('input', function(e) {
        // Remove non-numeric characters
        let value = e.target.value.replace(/[^\d]/g, '');

        // Format with thousand separators
        if (value) {
            e.target.value = new Intl.NumberFormat('id-ID').format(value);
        }
    });

    // Set min date to today for closing date
    document.getElementById('closing_date').min = new Date().toISOString().split('T')[0];
</script>