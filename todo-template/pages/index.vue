<script setup>
import draggable from "vuedraggable";
import axios from "~/plugins/axios";

const $axios = axios().provide.axios;

const tasks = ref([]);
const task = reactive({
  title: null,
});
const editTask = reactive({
  id: null,
  title: null,
});
// const dragIndex = ref(null);
const isEditDialog = ref(false);
const errorAdd = ref(null);
const errorEdit = ref(null);

onMounted(() => {
  getAll();
});

const getAll = async () => {
  await $axios
    .get("/tasks")
    .then((res) => {
      tasks.value = res.data;
    })
    .catch((e) => {
      console.log(e);
    });
};

const addTask = async () => {
  errorAdd.value = null;
  if (!task.title) {
    errorAdd.value = "Please fill out this field";
    return;
  }
  await $axios
    .post("/tasks", task)
    .then((res) => {
      tasks.value.push(res.data);
      task.title = null;
    })
    .catch((e) => {
      console.log(e);
    });
};

const deleteTask = async (id) => {
  await $axios
    .delete("/tasks/" + id)
    .then((res) => {
      var newArr = tasks.value.filter((x) => x.id != id);
      tasks.value = newArr;
    })
    .catch((e) => {
      console.log(e);
    });
};

const openDialogEdit = (item) => {
  isEditDialog.value = true;
  editTask.id = item.id;
  editTask.title = item.title;
};

const updateTask = async () => {
  errorEdit.value = null;
  if (!editTask.title) {
    errorEdit.value = "Please fill out this field";
    return;
  }
  await $axios
    .put("/tasks/" + editTask.id, editTask)
    .then((res) => {
      const index = tasks.value.findIndex((x) => x.id == editTask.id);
      tasks.value[index].title = editTask.title;
      isEditDialog.value = false;
    })
    .catch((e) => {
      console.log(e);
    });
};

// const startDrag = (event, index) => {
//   dragIndex.value = index;
//   event.dataTransfer.effectAllowed = "move";
// };

// const dragOver = (event, index) => {
//   event.preventDefault();
//   event.dataTransfer.dropEffect = "move";
// };
// const drop = (event, index) => {
//   event.preventDefault();
//   const item = tasks.value[dragIndex.value];
//   tasks.value.splice(dragIndex.value, 1);
//   tasks.value.splice(index, 0, item);
//   dragIndex.value = null;
// };
</script>
<template>
  <div class="bg-[#EEEEEE] h-[100vh]">
    <div class="w-1/3 mx-auto">
      <h3 class="text-2xl font-bold text-center">ToDo List</h3>
      <div class="mt-5">
        <form @submit.prevent="addTask()">
          <label for="title">New ToDo</label><br />
          <input
            type="text"
            class="w-full border mt-2 px-3 py-2 rounded-md"
            placeholder="e.g. make a cake"
            v-model="task.title"
          />
          <span v-if="errorAdd" class="text-red-400">{{ errorAdd }}</span>
          <button
            type="submit"
            class="w-full bg-blue-400 text-white mt-5 py-2.5 rounded-md"
          >
            Add ToDo
          </button>
        </form>
      </div>
      <h4 class="mt-8 mb-2 text-xl">Tasks</h4>
      <hr />
      <!-- <div class="w-full flex flex-col items-center gap-5 mt-5"> -->
      <draggable v-model="tasks">
        <template #item="{ element: task }">
          <div
            class="w-full bg-white my-5 flex items-center justify-between border border-black p-2.5 rounded-md"
          >
            <div>{{ task.title }}</div>
            <div>
              <button
                class="bg-[#42A5F5] text-white px-3"
                @click="openDialogEdit(task)"
              >
                Edit
              </button>
              <button
                @click="deleteTask(task.id)"
                class="bg-red-400 text-white ml-2.5 px-3"
              >
                Delete
              </button>
            </div>
          </div>
        </template>
      </draggable>
      <!-- <div
          v-for="(item, index) in tasks"
          :key="item.id"
          class="w-full bg-white flex items-center justify-between border border-black p-2.5 rounded-md"
          draggable="true"
          @dragstart="startDrag($event, index)"
          @dragover="dragOver($event, index)"
          @drop="drop($event, index)"
        >
          <div>{{ item.title }}</div>
          <div>
            <button
              class="bg-[#42A5F5] text-white px-3"
              @click="openDialogEdit(item)"
            >
              Edit
            </button>
            <button
              @click="deleteTask(item.id)"
              class="bg-red-400 text-white ml-2.5 px-3"
            >
              Delete
            </button>
          </div>
        </div> -->
      <!-- </div> -->
    </div>
  </div>
  <div
    v-if="isEditDialog"
    class="fixed bg-black bg-opacity-40 inset-0 w-full z-40 flex items-center justify-center h-[100vh] overflow-hidden"
  >
    <div class="w-1/3 p-3 rounded-md bg-white">
      <h3 class="text-xl font-bold">Edit Task</h3>
      <div class="mt-2.5">
        <form @submit.prevent="updateTask()">
          <input
            type="text"
            class="px-3 py-2 border w-full"
            placeholder="abc"
            v-model="editTask.title"
          />
          <span v-if="errorEdit" class="text-red-400">{{ errorEdit }}</span>
          <div class="flex gap-5">
            <button
              type="submit"
              class="w-full bg-green-500 mt-2.5 py-2.5 text-white rounded-md"
            >
              Save
            </button>
            <button
              class="w-full bg-red-500 mt-2.5 py-2.5 text-white rounded-md"
              @click="(isEditDialog = false), (errorEdit = null)"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>