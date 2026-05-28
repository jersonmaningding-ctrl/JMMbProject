// ====================================
// UTILITY FUNCTIONS
// ====================================
function qs(selector, root = document) {
    return root.querySelector(selector);
}

function qsa(selector, root = document) {
    return Array.from(root.querySelectorAll(selector));
}

function csrfToken() {
    const token = qs('input[name="_token"]');
    return token ? token.value : '';
}

function showMessage(target, message, type = 'success') {
    if (!target) {
        return;
    }

    target.classList.remove('alert-success', 'alert-danger');
    target.classList.add(type === 'success' ? 'alert-success' : 'alert-danger');
    target.textContent = message;
    target.style.display = 'block';
}

function activeAlert() {
    return qs('#studentAlert') || qs('#editStudentAlert');
}

function clearErrors(form) {
    qsa('.is-invalid', form).forEach((input) => input.classList.remove('is-invalid'));
    qsa('.invalid-feedback', form).forEach((feedback) => {
        feedback.textContent = '';
    });
}

function showValidationErrors(form, errors) {
    Object.keys(errors).forEach((field) => {
        const input = qs(`[name="${field}"]`, form);

        if (!input) {
            return;
        }

        input.classList.add('is-invalid');
        const group = input.closest('.form-group');
        const feedback = group ? qs('.invalid-feedback', group) : null;

        if (feedback) {
            feedback.textContent = errors[field][0];
        }
    });
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value || '';
    return div.innerHTML;
}

function degreeBadge(student) {
    if (!student.degree) {
        return '<span class="badge badge-gray">N/A</span>';
    }

    return `<span class="badge badge-blue">${escapeHtml(student.degree.name)}</span>`;
}

function updateStudentRow(student) {
    const row = qs(`#studentsTable tr[data-student-id="${student.id}"]`);

    if (!row) {
        return;
    }

    const cells = row.querySelectorAll('td');
    const middleName = student.mname ? ` ${escapeHtml(student.mname)}` : '';

    cells[1].innerHTML = `<strong>${escapeHtml(student.lname)}</strong>, ${escapeHtml(student.fname)}${middleName}`;
    cells[2].textContent = student.email || '-';
    cells[3].innerHTML = `<code style="font-size: 0.85rem;">${escapeHtml(student.username || '-')}</code>`;
    cells[4].textContent = student.age;
    cells[5].innerHTML = degreeBadge(student);
}

function setButtonLoading(form, isLoading) {
    const button = qs('[type="submit"]', form);

    if (!button) {
        return;
    }

    if (isLoading) {
        button.dataset.originalText = button.textContent;
        button.textContent = 'Saving...';
        button.disabled = true;
        return;
    }

    button.textContent = button.dataset.originalText || 'Save';
    button.disabled = false;
}

function showModal(modalElement) {
    if (window.bootstrap && window.bootstrap.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
        return;
    }

    modalElement.style.display = 'block';
    modalElement.classList.add('show');
    modalElement.removeAttribute('aria-hidden');
    document.body.classList.add('modal-open');
}

function hideModal(modalElement) {
    if (window.bootstrap && window.bootstrap.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(modalElement).hide();
        return;
    }

    modalElement.style.display = 'none';
    modalElement.classList.remove('show');
    modalElement.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('modal-open');
}

function fillStudentForm(form, student) {
    qs('[name="fname"]', form).value = student.fname || '';
    qs('[name="mname"]', form).value = student.mname || '';
    qs('[name="lname"]', form).value = student.lname || '';
    qs('[name="email"]', form).value = student.email || '';
    qs('[name="contact"]', form).value = student.contact || '';
    qs('[name="age"]', form).value = student.age || '';
    qs('[name="degree_id"]', form).value = student.degree_id || '';
    qs('[name="username"]', form).value = student.username || '';
    qs('[name="password"]', form).value = '';
    qs('[name="password"]', form).required = false;
}

function resetModalForm(form) {
    const page = qs('#studentsAjaxPage');

    form.reset();
    clearErrors(form);
    form.action = page.dataset.storeUrl;
    qs('[name="_method"]', form).value = 'POST';
    qs('#studentModalLabel').textContent = 'Add Student';
    qs('#saveStudentBtn').textContent = 'Save Student';
    qs('[name="password"]', form).required = true;
}

// ====================================
// LOAD/READ - Auto Reload Students
// ====================================
window.autoReloadStudents = function(url = null) {
    const page = qs('#studentsAjaxPage');
    const table = qs('#studentsTable');

    if (!page || !table) {
        return;
    }

    const targetUrl = url || page.dataset.currentUrl || page.dataset.indexUrl;
    page.dataset.currentUrl = targetUrl;

    const requestUrl = new URL(targetUrl, window.location.origin);
    requestUrl.searchParams.set('_', Date.now());

    $.ajax({
        url: requestUrl.toString(),
        type: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        dataType: 'json',
        success: function(data) {
            table.innerHTML = data.html;
        }
    });
};

// ====================================
// STORE - Create Student
// ====================================
window.requestStudentSave = function(form) {
    clearErrors(form);
    setButtonLoading(form, true);

    return $.ajax({
        url: form.action,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        data: new FormData(form),
        processData: false,
        contentType: false,
        dataType: 'json'
    }).done(function(data) {
        setButtonLoading(form, false);
        return data;
    }).fail(function(xhr) {
        const data = xhr.responseJSON;
        
        if (xhr.status === 422 && data.errors) {
            showValidationErrors(form, data.errors);
            setButtonLoading(form, false);
            return null;
        }

        showMessage(activeAlert(), data.message || 'Unable to save student. Please try again.', 'danger');
        setButtonLoading(form, false);
        return null;
    });
};

window.createStudent = function(event, form) {
    event.preventDefault();
    event.stopPropagation();

    window.requestStudentSave(form).done(function(data) {
        if (!data) {
            return false;
        }

        hideModal(qs('#studentModal'));
        showMessage(qs('#studentAlert'), data.message);
        window.autoReloadStudents();
    });
    return false;
};

// ====================================
// UPDATE - Edit Student
// ====================================
window.updateStudent = function(event, form) {
    event.preventDefault();
    event.stopPropagation();

    window.requestStudentSave(form).done(function(data) {
        if (!data) {
            return false;
        }

        showMessage(qs('#editStudentAlert'), data.message);
        updateStudentRow(data.student);
    });
    return false;
};

// ====================================
// DELETE - Delete Student
// ====================================
window.deleteStudent = function(deleteButton) {
    if (!confirm('Delete this student?')) {
        return;
    }

    const body = new FormData();
    body.append('_method', 'DELETE');

    $.ajax({
        url: deleteButton.dataset.url,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        data: body,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(data) {
            deleteButton.closest('tr').remove();
            showMessage(qs('#studentAlert'), data.message);
            window.autoReloadStudents();
        }
    });
};

// ====================================
// INITIALIZE - DOM Ready
// ====================================
document.addEventListener('DOMContentLoaded', function () {
    const page = qs('#studentsAjaxPage');
    const modalElement = qs('#studentModal');
    const modalForm = qs('#studentForm');
    const editForm = qs('#studentEditForm');

    if (editForm) {
        editForm.addEventListener('submit', function (event) {
            window.updateStudent(event, editForm);
        });
    }

    if (!page || !modalElement || !modalForm) {
        return;
    }

    page.dataset.currentUrl = page.dataset.indexUrl;

    qs('#addStudentBtn').addEventListener('click', function () {
        resetModalForm(modalForm);
        showModal(modalElement);
    });

    modalForm.addEventListener('submit', function (event) {
        window.createStudent(event, modalForm);
    });

    qsa('[data-bs-dismiss="modal"]', modalElement).forEach((button) => {
        button.addEventListener('click', function () {
            hideModal(modalElement);
        });
    });

    qs('#studentsTable').addEventListener('click', function (event) {
        const editButton = event.target.closest('.js-edit-student');
        const deleteButton = event.target.closest('.js-delete-student');
        const pageLink = event.target.closest('.ajax-pagination a');

        if (editButton) {
            resetModalForm(modalForm);

            $.ajax({
                url: editButton.dataset.url,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                dataType: 'json',
                success: function(student) {
                    fillStudentForm(modalForm, student);
                    modalForm.action = editButton.dataset.updateUrl;
                    qs('[name="_method"]', modalForm).value = 'PUT';
                    qs('#studentModalLabel').textContent = 'Edit Student';
                    qs('#saveStudentBtn').textContent = 'Update Student';
                    showModal(modalElement);
                }
            });
            return;
        }

        if (deleteButton) {
            window.deleteStudent(deleteButton);
            return;
        }

        if (pageLink) {
            event.preventDefault();
            window.autoReloadStudents(pageLink.href);
        }
    });
});
