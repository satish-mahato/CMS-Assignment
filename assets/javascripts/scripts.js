document.addEventListener("DOMContentLoaded", () => {
  const taskInput = document.getElementById("taskInput");
  const addTaskBtn = document.getElementById("addTaskBtn");
  const deleteCompletedBtn = document.getElementById("deleteCompletedBtn");
  const taskList = document.querySelector(".task-list");

  addTaskBtn.addEventListener("click", () => {
    if (taskInput.value.trim() !== "") {
      const taskDiv = document.createElement("div");
      taskDiv.classList.add("task");

      const checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.addEventListener("change", updateDeleteButton);

      const label = document.createElement("label");
      label.textContent = taskInput.value;

      taskDiv.appendChild(checkbox);
      taskDiv.appendChild(label);
      taskList.appendChild(taskDiv);

      taskInput.value = "";
      updateDeleteButton();
    }
  });

  deleteCompletedBtn.addEventListener("click", () => {
    document.querySelectorAll(".task input:checked").forEach((checkbox) => {
      checkbox.parentElement.remove();
    });
    updateDeleteButton();
  });

  function updateDeleteButton() {
    const completedTasks = document.querySelectorAll(".task input:checked");
    deleteCompletedBtn.style.display =
      completedTasks.length > 0 ? "block" : "none";
  }
});
