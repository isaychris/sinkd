<!--
//===================================================================================================
// File: main.js
// Description: This script file provides the javascript functions & variables for the main page. 
//===================================================================================================
-->


//------------------//
// GLOBAL VARIABLES //
//------------------//
var crumbarray = []; // a stack to keep track of the users current location within the file system hieirachy.
var user_dir;

// PURPOSE: Clears the table rows for use of repopulating the table.
function clear() {
  $('.tablesorter tbody tr').remove();
  $('.tablesorter').trigger('updateAll');
}

// PURPOSE: Returns name of directory.
function getDirName(dir_path) {
  // split the directory name into a string array and pop the last directory.
  var dir_name = dir_path.split("/").pop();
  return dir_name;
}

// PURPOSE: Shows alert messages when user executes an action.
// PARAMS: type = the type of messsage (success, warning, danger, eg.)
//         message = the message to display
function showAlert(type, message) {
  // change the content of alert div 
  $('#alert').html("<div class='alert alert-" + type + "' role='alert'>" + message + "</div>");
  $('#alert').show();

  // alert animation
  $("#alert").fadeTo(5000, 1000).slideUp(1000, function() {
    $("#alert").slideUp(1000);
  });
}

// PURPOSE: Adds a directory to the breadcrumb.
// PARAMS:  dir = the directory to add
function addCrumb(dir) {
  var crumb = '<span><a class="breadcrumbs-item change" href="" data-path="' + dir + '">' + getDirName(dir) + '</a> / </span>';
  crumbarray.push(crumb);

  $('.breadcrumb').append(crumbarray[crumbarray.length - 1]);
}

// PURPOSE: Removes directories from the breadcrumb.
// PARAMS:  pops = number of times to pop the array to get to selected location.
//	    crumb = the location to remove from the breadcrumb.
function removeCrumb(pops, crumb) {
  for (i = 0; i < pops; i++) {
    crumbarray.pop();
    $('.breadcrumb').find("span").last().remove();
  }
}

// PURPOSE: When page is ready, perform these actions.
$(document).ready(function() {
  $("#myTable").tablesorter({
    theme: 'bootstrap',
    ignoreCase: true,
    sortList: [
      [0, 0],
      [0, 0]
    ]
  });

  // initialize the breadcrumb and table with the current directory.
  user_dir = current_dir;
  $("#display").text(current_display);
  addCrumb(current_dir);
  getData();
});

// PURPOSE: Gets file data from server using ajax and populates the table.
function getData() {
  clear();

  $.ajax({
    type: 'POST',
    url: 'main/populate.php',
    data: {
      userdir: current_dir
    },
    dataType: 'json',
    success: function(result) {

      // case 0: theres no files in directory, so append empty row.
      if (result.length == 0) {
        $row = '<tr><td colspan="4" class="empty"> Empty </td></tr>';

        $('#myTable').find('tbody').append($row);
        $('.tablesorter').trigger('updateAll');
      } else {
        for (i = 0; i < result.length; i++) {

          // case 1 = its a file
          if (result[i].type == 'true') {
            $file_name = '<td>' + result[i].name + '</td>';
            $file_mod = '<td>' + result[i].mod + '</td>';
            $file_size = '<td>' + result[i].size + '</td>';
            $file_delete = '<button title="delete" class="btn btn-danger btn-sm delete" data-path="' + result[i].path + '"><span class="fa fa-trash"></span></button>';
            $file_edit = '<button title="edit" class="btn btn-success btn-sm rename" data-path="' + result[i].name + '"><span class="fa fa-pencil"></span></button>'
            $file_view = '<button title="view" class="btn btn-primary btn-sm view" data-path="' + result[i].web + '"><span class="fa fa-download"></span></button>';
            $file_share = '<button title="share" class="btn btn-outline-secondary btn-sm share" data-path="' + result[i].web + '"><span class="fa fa-link"></span></button>';
            $file_buttons = '<td>' + $file_delete + ' ' + $file_edit + ' ' + $file_view + ' ' + $file_share + '</td>';

            $row = '<tr>' + $file_name + $file_size + $file_mod + $file_buttons + '</tr>';
          }

          // case 2 = its a folder
          else {
            $file_name = '<td><a class="change" href="" data-path="' + result[i].path + '"><span class="fa fa-folder">&nbsp;</span>' + '[' + result[i].name + ']' + '</td>';
            $file_mod = '<td>' + result[i].mod + '</td>';
            $file_size = '<td> -- </td>';
            $file_delete = '<button title="delete" class="btn btn-danger btn-sm delete" data-path="' + result[i].path + '"><span class="fa fa-trash"></span></button>';
            $file_edit = '<button title="edit" class="btn btn-success btn-sm rename" data-path="' + result[i].name + '"><span class="fa fa-pencil"></span></button>'
            $file_buttons = '<td>' + $file_delete + ' ' + $file_edit + '</td>';

            $row = '<tr>' + $file_name + $file_size + $file_mod + $file_buttons + '</tr>';
          }
          
          // after creating the table row, append the row to the table body and update table cache.
          $('#myTable').find('tbody').append($row);
          $('.tablesorter').trigger('updateAll');
        }
      }
    },
    error: function() {
      showAlert('danger', '<strong>Error!</strong> Unable to change directories.');
    }
  });

  return false;
}

// PURPOSE: Changes the current directory path of user and updates the breadcrumb.
$(document).on('click', 'a.change', function() {
  // change the current directory and repopulate table with new data.
  current_dir = $(this).attr("data-path");
  getData();

  var crumb = '<span><a class="breadcrumbs-item change" href="" data-path="' + current_dir + '">' + getDirName(current_dir) + '</a> / </span>';

  // check if crumb already exists in array.
  // if it exists pop array until last array is equal to existing aray.
  // else, add the crumb to the crumbarray.
  if (crumbarray.indexOf(crumb) != -1) {
    var pops = (crumbarray.length - crumbarray.indexOf(crumb) - 1);
    removeCrumb(pops, crumb);
  } else {
    addCrumb(current_dir);
  }

  return false;
});

// PURPOSE: Uploads a single file to the server through an ajax call.
$(document).on('change', 'input.upload', function() {

  // retrieve file info from fileinput element
  var property = $(this)[0].files[0];
  var file_name = property.name;
  var file_size = property.size;

  // Max upload size through php is 2MB. This can only be changed by the server admin..
  if (file_size >= 2000000) {
    alert("File is too big. Max size: 2MB");
  } else {

    // place file info into a form data object so it can be parsed through an ajax call.
    var file_data = new FormData();
    file_data.append("file", property);
    file_data.append("dir", current_dir);

    $.ajax({
      type: 'POST',
      url: 'main/upload.php',
      data: file_data,
      contentType: false,
      processData: false,
      success: function(result) {
        getData();
        if (result == 0) {
          showAlert('success', '<strong>Success!</strong> The file <mark class="green">' + file_name + '</mark> was uploaded.');
        } else {
          showAlert('primary', '<strong>Woops!</strong> The file name <mark class="blue">' + file_name + '</mark> already exists. Renaming with iterator.');
        }
      },
      error: function() {
        showAlert('danger', '<strong>Error!</strong> Unable to upload file <mark class="red">' + file_name + '</mark>');
      }
    });
  }

  // clear the file selection after uploading.
  $(this).val('');

  return false;
});

// PURPOSE: Renames the selected file/folder using an ajax call.
$(document).on('click', 'button.rename', function() {

  // get the oldname and set it as the initial value in the prompt.
  var oldname = $(this).attr("data-path");
  var newname = prompt("Enter a new name:", oldname);

  // if the input is blank or user clicks on cancel
  // or the newname is the same as the old name, end the rename operation.
  if (newname == null || newname == '' || newname == oldname) {
    return;
  }

  $.ajax({
    type: 'POST',
    url: 'main/rename.php',
    data: {
      dir: current_dir,
      old: oldname,
      new: newname
    },
    success: function(result) {
      getData();

      if (result == 0) {
        showAlert('success', '<strong>Success!</strong> The file/folder was renamed to <mark class="green">' + newname + '</mark>');
      } else {
        showAlert('primary', '<strong>Woops!</strong> The file/folder name <mark class="blue">' + newname + '</mark> already exists. Renaming with iterator.');
      }
    },
    error: function() {
      showAlert('danger', '<strong>Error!</strong> Unable to rename file <mark class="red">' + oldname + '</mark>');
    }
  });
  return false;
});

// PURPOSE: Opens the file in a new window.
$(document).on('click', 'button.view', function() {
  $webfilepath = $(this).attr("data-path")
  open($webfilepath, "_blank");
});

// PURPOSE: Displays the account settings window.
$(document).on('click', 'button.account', function() {
  $('#myModal').modal();
});

// PURPOSE: Copies the file link to the users clipboard.
$(document).on('click', 'button.share', function() {

  // text from an input element can be copied by executing copy command.
  // append input element temporarily to the body and use the data-path 
  // of the selected table row as the text.
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(encodeURI($(this).attr("data-path"))).select();
  document.execCommand("copy"); // this copies the text
  $temp.remove();

  showAlert('success', '<strong>Success!</strong> The file link was copied to your clipboard.');
});

// PURPOSE: creates a new folder using an ajax call.
$(document).on('click', 'button.create', function() {
  var name = prompt("Enter a name for folder:");

  // if the input is blank or user clicks on cancel, end the new folder operation.
  if (name == null || name == '') {
    return;
  }

  $.ajax({
    type: 'POST',
    url: 'main/create.php',
    data: {
      userdir: current_dir,
      name: name
    },
    success: function(result) {
      getData();

      if(result == 0) {
        showAlert('success', '<strong>Success!</strong> A folder called <mark class="green">' + name + '</mark> was created.');
      } else {
        showAlert('primary', '<strong>Woops!</strong> A folder called <mark class="blue">' + name + '</mark> already exists. Renaming with iterator.');
      }
    },
    error: function() {
      showAlert('danger', '<strong>Error!</strong> Unable to create a new folder.');
    }
  });
  return false;
});

// PURPOSE: deletes the selected file using an ajax call.
$(document).on('click', 'button.delete', function() {
  var result = confirm("Are you sure you want to delete?");
  $row = $(this).closest("tr");

  if (result == true) {
    var delete_path = $(this).attr("data-path");
    var file_name = delete_path.split("/").pop();

    $.ajax({
      type: 'POST',
      url: 'main/delete.php',
      data: {
        file: delete_path
      },
      success: function() {
        // remove the row from the table and show alert.
        $row.remove();
        $('.tablesorter').trigger('updateAll');

        // afted deleting row, if table is empty, place empty row in table.
        if ($('table tbody tr').length == 0) {
          $row = '<tr><td colspan="4" class="empty"> Empty </td></tr>';

          $('#myTable').find('tbody').append($row);
          $('.tablesorter').trigger('updateAll');
        }
        showAlert('success', '<strong>Success!</strong> The file/folder <mark class="green">' + file_name + '</mark> was deleted.');
      },
      error: function() {
        showAlert('danger', '<strong>Error!</strong> Unable to delete file/folder <mark class="red">' + file_name + '</mark>');
      }
    });
  }
  return false;
});

// =====================================================ACCOUNT SETTINGS========================================================
//Edit password function
$(document).on('click', 'button.EditPass', function() {
  var newpass = prompt("Enter a new password:");

  if (newpass == null || newpass == '') {
    return;
  }

  var result = confirm('Are you sure you want to change passwords to ' + newpass +'?');
//Post new password and username to server
  if (result == true) {
    $.ajax({
      type: 'POST',
      url: 'main/change_pass.php',
      data: {
        user: current_user,
        pass: newpass,
      },
      success: function() {
        showAlert('success', '<strong>Success!</strong> Your password was changed.');
      },
      error: function() {
        showAlert('danger', '<strong>Error!</strong> Unable to change password.');
      }
    });
  }
  return false;
});
//Logout function, returns to login page
$(document).on('click', 'button.logout', function() {
  var result = confirm("Are you sure you want to logout?");
  //Sets window page to login screen
  if (result == true) {
    window.location = "logout.php";
  }
});
//Edit display name function, updates displayname on main  page
$(document).on('click', 'button.EditName', function() {
  var name = prompt("Enter a new display name:", current_display);
  if (name == null || name == '') {
    return;
  }
  //Post old display name and new display name to server 
  $.ajax({
    type: 'POST',
    url: 'main/change_display.php',
    data: {
      oldname: current_display,
      newname: name,
    },
	//Update main page display name 
    success: function() {
      current_display = name;
      $("#display").text(current_display);
      showAlert('success', '<strong>Success!</strong> Your display name was changed to <mark class="green">' + current_display + '</mark>');

    },
    error: function() {
      showAlert('danger', '<strong>Error!</strong> Unable to change display name to <mark class="red">' + current_display + '</mark>');
    }
  });
  return false;
});

// PURPOSE: Deletes the users account.
$(document).on('click', 'button.wipe', function() {
  var result = confirm("Are you sure you want to delete your account?");

  
  if (result == true) {
    $.ajax({
      type: 'POST',
      url: 'main/delete_account.php',
      data: {
        user_path: user_dir,
	user: current_user
      },
      success: function() {
        window.location = "logout.php";
      },
      error: function() {
        showAlert('danger', '<strong>Error!</strong> Unable to delete account.');
      }
    });
  }
  return false;
});

// PURPOSE: gives focus to bootstrap modal.
$('#myModal').on('shown.bs.modal', function() {
  $('#myInput').trigger('focus');
});