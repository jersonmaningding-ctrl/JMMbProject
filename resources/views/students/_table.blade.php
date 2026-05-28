<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Age</th>
                <th>Degree</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr data-student-id="{{ $student->id }}">
                    <td>{{ $student->id }}</td>
                    <td><strong>{{ $student->lname }}</strong>, {{ $student->fname }} {{ $student->mname }}</td>
                    <td>{{ $student->email ?? '-' }}</td>
                    <td><code style="font-size: 0.85rem;">{{ $student->username ?? '-' }}</code></td>
                    <td>{{ $student->age }}</td>
                    <td>
                        @if($student->degree)
                            <span class="badge badge-blue">{{ $student->degree->name }}</span>
                        @else
                            <span class="badge badge-gray">N/A</span>
                        @endif
                    </td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('students.show', $student) }}" class="btn btn-secondary btn-sm">View</a>
                            <button
                                type="button"
                                class="btn btn-warning btn-sm js-edit-student"
                                data-url="{{ route('students.show', $student) }}"
                                data-update-url="{{ route('students.update', $student) }}"
                            >
                                Edit
                            </button>
                            <button
                                type="button"
                                class="btn btn-danger btn-sm js-delete-student"
                                data-url="{{ route('students.destroy', $student) }}"
                            >
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <p>No students found. Use the Add Student button to create one.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination ajax-pagination">{{ $students->links() }}</div>
