<!DOCTYPE html>
<html>
<head>
    <title>FullCalendar Integration with Native PHP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

    <script>
        $(document).ready(function () {
            var calendar = $('#calendar').fullCalendar({
                editable: true,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: "events.php?action=load",
                selectable: true,
                selectHelper: true,
                select: function (start, end, allDay) {
                    const userInput = document.getElementById("userInput");
                    const modal = document.getElementById("myModal");
                    const deleteButton = document.getElementById("closeModal");
                    const updateButton = document.getElementById("submitModal");
                    const errorMessage = document.getElementById("errorMessage");


                    errorMessage.textContent = "";
                    userInput.value = "";
                    modal.style.display = "flex";
                    deleteButton.style.backgroundColor = "#403d3d";
                    deleteButton.textContent = "Cancel";
                    updateButton.textContent = "Add";

                    var startFormatted = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var endFormatted = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                    updateButton.replaceWith(updateButton.cloneNode(true));
                    deleteButton.replaceWith(deleteButton.cloneNode(true));

                    const newUpdateButton = document.getElementById("submitModal");
                    const newDeleteButton = document.getElementById("closeModal");

                    newUpdateButton.addEventListener("click", function () {
                        if (userInput.value) {
                            $.ajax({
                                url: "events.php?action=insert",
                                type: "POST",
                                data: { title: userInput.value, start: startFormatted, end: endFormatted },
                            success: function () {
                                calendar.fullCalendar('refetchEvents');
                                alert("Added Successfully");
                                modal.style.display = "none";
                            }
                        });
                    } else {
                        errorMessage.textContent = "Please fill up the input";
                        }
                    });

                    newDeleteButton.addEventListener("click", function () {
                        modal.style.display = "none";
                    });
                },
                eventClick: function (event) {
                    const errorMessage = document.getElementById("errorMessage");
                    const userInput = document.getElementById("userInput");
                    const modal = document.getElementById("myModal");
                    const deleteButton = document.getElementById("closeModal");
                    const updateButton = document.getElementById("submitModal");

                    errorMessage.textContent = "";
                    userInput.value = event.title;
                    modal.style.display = "flex";
                    deleteButton.style.backgroundColor = "red";
                    deleteButton.textContent = "Delete";
                    updateButton.textContent = "Update";

                    var id = event.id;
                    var startFormatted = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var endFormatted = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");

                    updateButton.replaceWith(updateButton.cloneNode(true));
                    deleteButton.replaceWith(deleteButton.cloneNode(true));

                    const newUpdateButton = document.getElementById("submitModal");
                    const newDeleteButton = document.getElementById("closeModal");

                    newDeleteButton.addEventListener("click", function () {
                        $.ajax({
                            url: "events.php?action=delete",
                            type: "POST",
                            data: { id: id },
                            success: function () {
                                calendar.fullCalendar('refetchEvents');
                                modal.style.display = "none";
                                alert("Event Removed");
                            }
                        });
                    });

                    newUpdateButton.addEventListener("click", function () {
                        if (userInput.value) {
                            $.ajax({
                                url: "events.php?action=update",
                                type: "POST",
                                data: { title: userInput.value, start: startFormatted, end: endFormatted, id: id },
                                success: function () {
                                    calendar.fullCalendar('refetchEvents');
                                    modal.style.display = "none";
                                    alert("Event Updated");
                                }
                            });
                        } else {
                            errorMessage.textContent = "Please fill up the input";
                        }
                    });
                }
            });
        });
 </script>
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        #details{
            padding: 20px;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .close-button {
            background-color: red;
            color: white;
        }

        .submit-button {
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
    <div class="modal" id="myModal">
        <div class="modal-content">
            <h2 id="details">Enter Details</h2>
            <input type="text" id="userInput" placeholder="Type something...">
            <h5 id="errorMessage" style="color: red"></h5>
            <div class="modal-buttons">
                <button class="close-button"  id="closeModal">Delete</button>
                <button class="submit-button" id="submitModal">Update</button>
            </div>
        </div>
    </div>


    <br />
    <h2 align="center">FullCalendar</h2>
    <br />
    <div class="container">
        <div id="calendar"></div>
    </div>

    <script>
        // JavaScript for modal functionality
        const modal = document.getElementById("myModal");
        const openModalBtn = document.getElementById("openModal");
        const closeModalBtn = document.getElementById("closeModal");
        const submitModalBtn = document.getElementById("submitModal");
        const userInput = document.getElementById("userInput");

        openModalBtn.addEventListener("click", () => {
            modal.style.display = "flex";
        });

        closeModalBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        submitModalBtn.addEventListener("click", () => {
            const value = userInput.value;
            alert("You entered: " + value);
            modal.style.display = "none";
        });

        // Close modal when clicking outside the modal content
        window.addEventListener("click", (event) => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    </script>
</body>
</html>
