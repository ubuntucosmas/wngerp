<div class="row g-3">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ isset($modal) ? 'modal_name' : 'name' }}" class="form-label fw-semibold">Name</label>
            <input type="text" name="name" id="{{ isset($modal) ? 'modal_name' : 'name' }}" class="form-control rounded-pill @error('name') is-invalid @enderror"
                   value="{{ old('name', $user->name ?? '') }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ isset($modal) ? 'modal_email' : 'email' }}" class="form-label fw-semibold">Email</label>
            <input type="email" name="email" id="{{ isset($modal) ? 'modal_email' : 'email' }}" class="form-control rounded-pill @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email ?? '') }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ isset($modal) ? 'modal_password' : 'password' }}" class="form-label fw-semibold">
                {{ isset($user) ? 'New Password (optional)' : 'Password' }}
            </label>
            <input type="password" name="password" id="{{ isset($modal) ? 'modal_password' : 'password' }}" class="form-control rounded-pill @error('password') is-invalid @enderror"
                   @if(!isset($user)) required @endif placeholder="{{ isset($user) ? 'Leave blank to keep current password' : '' }}">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ isset($modal) ? 'modal_password_confirmation' : 'password_confirmation' }}" class="form-label fw-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" id="{{ isset($modal) ? 'modal_password_confirmation' : 'password_confirmation' }}" class="form-control rounded-pill"
                   @if(!isset($user)) required @endif>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ isset($modal) ? 'modal_role' : 'role' }}" class="form-label fw-semibold">Role</label>
            <select name="role" id="{{ isset($modal) ? 'modal_role' : 'role' }}" class="form-control rounded-pill @error('role') is-invalid @enderror" required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}"
                        {{ old('role', (isset($user) && $user->roles->first()) ? $user->roles->first()->name : '') == $role->name ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                    </option>
                @endforeach
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ isset($modal) ? 'modal_department' : 'department' }}" class="form-label fw-semibold">Department</label>
            <select name="department" id="{{ isset($modal) ? 'modal_department' : 'department' }}" class="form-control rounded-pill @error('department') is-invalid @enderror">
                <option value="">None</option>
                <option value="administration" {{ old('department', $user->department ?? '') == 'administration' ? 'selected' : '' }}>Administration</option>
                <option value="ict" {{ old('department', $user->department ?? '') == 'ict' ? 'selected' : '' }}>IT</option>
                <option value="projects" {{ old('department', $user->department ?? '') == 'projects' ? 'selected' : '' }}>Projects</option>
                <option value="hr" {{ old('department', $user->department ?? '') == 'hr' ? 'selected' : '' }}>HR</option>
                <option value="stores" {{ old('department', $user->department ?? '') == 'stores' ? 'selected' : '' }}>Stores</option>
                <option value="procurement" {{ old('department', $user->department ?? '') == 'procurement' ? 'selected' : '' }}>Procurement</option>
            </select>
            @error('department') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="{{ isset($modal) ? 'modal_level' : 'level' }}" class="form-label fw-semibold">Access Level (1-5)</label>
            <select name="level" id="{{ isset($modal) ? 'modal_level' : 'level' }}" class="form-control rounded-pill @error('level') is-invalid @enderror" required>
                <option value="">Select Level</option>
                <option value="1" {{ old('level', $user->level ?? '') == 1 ? 'selected' : '' }}>1 - Basic</option>
                <option value="2" {{ old('level', $user->level ?? '') == 2 ? 'selected' : '' }}>2 - Intermediate</option>
                <option value="3" {{ old('level', $user->level ?? '') == 3 ? 'selected' : '' }}>3 - Advanced</option>
                <option value="4" {{ old('level', $user->level ?? '') == 4 ? 'selected' : '' }}>4 - Senior</option>
                <option value="5" {{ old('level', $user->level ?? '') == 5 ? 'selected' : '' }}>5 - Highest</option>
            </select>
            @error('level') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div> 