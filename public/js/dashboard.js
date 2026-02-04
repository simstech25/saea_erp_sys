// Existing loadPage function
function loadPage(page) {
    fetch(`ajax/load.php?page=${page}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('content').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('content').innerHTML =
                "<p style='color:red'>Failed to load page</p>";
            console.error(err);
        });
}

// ----------------- Users management functions -----------------
function changeRole(id, role) {
    if (!id || !role) {
        console.error('Missing id or role parameter');
        return;
    }
    
    fetch('ajax/users_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'changeRole', id, role})
    }).then(res => res.json())
      .then(data => alert(data.message))
      .catch(err => console.error('Error changing role:', err));
}

function approveUser(id) {
    if (!id) {
        console.error('Missing id parameter');
        return;
    }
    
    fetch('ajax/users_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'approve', id})
    }).then(res => res.json())
      .then(data => {
          alert(data.message);
          if (data.success) {
              const statusCell = document.querySelector(`#user-row-${id} td:nth-child(5)`);
              const button = document.querySelector(`#user-row-${id} button`);
              if (statusCell) statusCell.innerText = 'Approved';
              if (button) button.remove();
          }
      })
      .catch(err => console.error('Error approving user:', err));
}

function editUser(id) {
    if (!id) {
        console.error('Missing id parameter');
        return;
    }
    
    // Open a modal or prompt
    const newName = prompt("Enter new full name:");
    if (!newName) return;
    
    const newEmail = prompt("Enter new email:");
    if (!newEmail) return;
    
    if (newName && newEmail) {
        fetch('ajax/users_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'edit', id, full_name: newName, email: newEmail})
        }).then(res => res.json())
          .then(data => {
              if (data.success) {
                  const row = document.getElementById(`user-row-${id}`);
                  if (row) {
                      // Better: Use class selectors instead of index positions
                      const nameCell = row.querySelector('.user-name');
                      const emailCell = row.querySelector('.user-email');
                      if (nameCell) nameCell.innerText = newName;
                      if (emailCell) emailCell.innerText = newEmail;
                  }
              }
              alert(data.message);
          })
          .catch(err => console.error('Error editing user:', err));
    }
}

function deleteUser(id) {
    if (!id) {
        console.error('Missing id parameter');
        return;
    }
    
    if (confirm("Are you sure you want to delete this user?")) {
        fetch('ajax/users_actions.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'delete', id})
        }).then(res => res.json())
          .then(data => {
              if (data.success) {
                  const row = document.getElementById(`user-row-${id}`);
                  if (row) row.remove();
              }
              alert(data.message);
          })
          .catch(err => console.error('Error deleting user:', err));
    }
} 