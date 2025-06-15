<!-- Poppins font already loaded -->
<style>
  body {
    font-family: 'Poppins', sans-serif;
  }

  .modal-content {
    border-radius: 1.2rem;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
    padding: 1.5rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
  }

  .modal-header,
  .modal-footer {
    border: none;
  }

  .modal-title {
    font-weight: 600;
    font-size: 1.1rem;
    color: #0c2d48;
  }

  .form-label {
    font-weight: 500;
    font-size: 0.875rem;
    color: #333;
  }

  .form-control,
  .form-select {
    border-radius: 0.75rem;
    padding: 0.5rem 0.75rem;
    border: 1px solid #ddd;
    background-color: #f9fafb;
    font-size: 0.9rem;
    transition: all 0.3s ease;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #2e8bc0;
    box-shadow: 0 0 0 0.2rem rgba(46, 139, 192, 0.25);
    background-color: #fff;
  }

  .form-control:hover,
  .form-select:hover {
    border-color: #145da0;
  }

  textarea.form-control {
    resize: vertical;
  }

 

  .btn-close {
    filter: brightness(0.6);
  }
</style>
<!-- Updated Create Task Modal -->
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form method="POST" action="{{ route('phases.tasks.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="phase_id" value="{{ $phase->id }}">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="createTaskModalLabel">Create Task</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label for="task_name" class="form-label">Task Name</label>
            <input type="text" class="form-control" name="name" id="task_name" required>
          </div>

          <div class="col-md-6">
            <label for="assigned_to" class="form-label">Assigned To</label>
            <input type="text" class="form-control" name="assigned_to" id="assigned_to">
          </div>

          <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" id="status" required>
              <option value="Pending">Pending</option>
              <option value="In Progress">In Progress</option>
              <option value="Completed">Completed</option>
            </select>
          </div>

          <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" name="start_date" id="start_date">
          </div>

          <div class="col-md-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" class="form-control" name="due_date" id="due_date">
          </div>

          <div class="col-12">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" rows="3"></textarea>
          </div>

          <div class="col-12">
            <label for="deliverables" class="form-label">Deliverables (one per line)</label>
            <textarea class="form-control" name="deliverables[]" rows="3" placeholder="E.g., Draft Report, Final Slides" oninput="splitDeliverables(this)"></textarea>
          </div>

          <div class="col-12">
            <label for="comments" class="form-label">Comments (optional)</label>
            <textarea class="form-control" name="comments[]" rows="2" placeholder="Enter a comment..."></textarea>
            <!-- Add more comment inputs dynamically if needed -->
          </div>

          <div class="col-12">
            <label for="attachments" class="form-label">Attachments</label>
            <input class="form-control" type="file" name="attachments[]" id="attachments" multiple
              accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
          </div>
        </div>

        <div class="modal-footer d-flex justify-content-end">
          <button type="submit" class="btn btn-outline-primary">Save Task</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function splitDeliverables(el) {
  const lines = el.value.split('\n').filter(Boolean);
  // Replace textarea input with hidden inputs (optional enhancement)
}
</script>

