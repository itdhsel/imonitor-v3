<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iMonitor v3 - Dashboard</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid mt-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>iMonitor Dashboard</h2>
            <!-- Button to trigger Add Modal -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">Add New Patient</button>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Date & Time</th>
                            <th>Ward</th>
                            <th>Patient Name (MRN)</th>
                            <th>Items</th>
                            <th>Supply</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->no }}</td>
                                <td>{{ $patient->date }} <br> {{ $patient->time }}</td>
                                <td>{{ $patient->ward }}</td>
                                <td>
                                    <strong>{{ $patient->patient_name }}</strong><br>
                                    <small class="text-muted">MRN: {{ $patient->mrn }}</small>
                                </td>
                                <td>{{ $patient->total_item }} / {{ $patient->total_item2 }}</td>
                                <td>{{ $patient->supply }}</td>
                                <td>
                                    @switch($patient->status)
                                        @case('PROCESSING')
                                            <span class="badge bg-warning text-dark">{{ $patient->status }}</span>
                                            @break
                                        @case('READY FOR COLLECTION')
                                            <span class="badge bg-primary">{{ $patient->status }}</span>
                                            @break
                                        @case('COMPLETED')
                                            <span class="badge bg-success">{{ $patient->status }}</span>
                                            @break
                                        @case('COLLECTED BY STAFF NURSE/PPK')
                                            <span class="badge bg-info text-dark">{{ $patient->status }}</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $patient->status }}</span>
                                    @endswitch
                                </td>
                                <td>{{ $patient->remarks }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <!-- Update Button -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateModal{{ $patient->no }}">Update</button>
                                        
                                        <!-- Delete Form -->
                                        <form action="{{ route('monitor.destroy', $patient->no) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                                </tr>

                            <!-- Update Modal for this specific Patient -->
                            <div class="modal fade" id="updateModal{{ $patient->no }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('monitor.update', $patient->no) }}" method="POST">
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Update Status: {{ $patient->patient_name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="PROCESSING" {{ $patient->status == 'PROCESSING' ? 'selected' : '' }}>PROCESSING</option>
                                                        <option value="READY FOR COLLECTION" {{ $patient->status == 'READY FOR COLLECTION' ? 'selected' : '' }}>READY FOR COLLECTION</option>
                                                        <option value="COMPLETED" {{ $patient->status == 'COMPLETED' ? 'selected' : '' }}>COMPLETED</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Remarks</label>
                                                    <input type="text" name="remarks" class="form-control" value="{{ $patient->remarks }}">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add New Patient Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('monitor.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Patient Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Basic fields required by the Controller validation -->
                            <div class="col-md-6 mb-3">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Time</label>
                                <input type="time" name="time" class="form-control" value="{{ date('H:i') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Patient Name</label>
                                <input type="text" name="patient_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>MRN</label>
                                <input type="text" name="mrn" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Ward</label>
                                <input type="text" name="ward" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Item Count</label>
                                <input type="number" name="total_item" class="form-control" value="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Item 2 Count</label>
                                <input type="number" name="total_item2" class="form-control" value="0" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Supply</label>
                                <select name="supply" class="form-select" required>
                                    <option value="1 MONTH">1 MONTH</option>
                                    <option value="FULL SUPPLY">FULL SUPPLY</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="PROCESSING">PROCESSING</option>
                                    <option value="READY FOR COLLECTION">READY FOR COLLECTION</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Record</button>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $patients->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS is required for the Modals to open -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>