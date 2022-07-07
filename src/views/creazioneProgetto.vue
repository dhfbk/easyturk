<template>
    <div v-if="loadingPage1 || loadingPage2"></div>
    <form
        v-else
        class="bg-white shadow-md rounded py-4 flex flex-col px-8 mt-4 mx-2 xs2:mx-4 lg:mx-auto lg:w-5/6 pt-2"
        @submit.prevent="caricaProgetto"
    >
        <p class="text-2xl mb-4 mt-0 text-primary">{{ pageTitle }}</p>
        <div class="-mx-3 md:flex md:flex-col">
            <div class="w-full px-3 mb-4">
                <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="name">Project Name</label>
                <input
                    :class="status > 2 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                    class="
                        appearance-none
                        block
                        w-full
                        border border-gray-200
                        rounded
                        py-2
                        px-4
                        mb-2
                        transition
                        duration-150
                        ease-out
                        focus:outline-none focus:border-gray-500
                        hover:border-gray-500
                    "
                    id="name"
                    type="text"
                    placeholder="Project name"
                    :maxlength="$store.state.defaults.max_length_name"
                    v-model.trim="$v.name.$model"
                    :disabled="status > 2"
                    required
                />
                <p class="text-gray-700 text-xs italic">This name is not displayed to Workers.</p>
            </div>
            <div class="w-full px-3 mb-4">
                <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="title">Title</label>
                <input
                    :class="status > 2 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                    class="
                        appearance-none
                        block
                        w-full
                        border
                        rounded
                        py-2
                        px-4
                        mb-2
                        transition
                        duration-150
                        ease-out
                        focus:outline-none focus:border-gray-500
                        hover:border-gray-500
                    "
                    id="title"
                    type="text"
                    placeholder="Title"
                    :maxlength="$store.state.defaults.max_length_title"
                    v-model.trim="$v.title.$model"
                    :disabled="status > 2"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Describe the survey to Workers. Be as specific as possible, e.g. "answer a survey about movies",
                    instead of "short survey", so Workers know what to expect.
                </p>
            </div>
            <div class="md:w-full px-3 mb-4">
                <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="keywords">Keywords</label>
                <input
                    :class="status > 2 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                    class="
                        appearance-none
                        block
                        w-full
                        border border-gray-200
                        rounded
                        py-2
                        px-4
                        mb-2
                        transition
                        duration-150
                        ease-out
                        focus:outline-none focus:border-gray-500
                        hover:border-gray-500
                    "
                    id="keywords"
                    type="text"
                    placeholder="Keywords"
                    v-model.trim="$v.keywords.$model"
                    :disabled="status > 2"
                    required
                />
                <p class="text-gray-700 text-xs italic">
                    Provide keywords that will help Workers search for your tasks.
                </p>
            </div>
            <div class="w-full px-3 mb-4">
                <label
                    class="block tracking-wide text-gray-900 text-md font-bold pb-2"
                    for="description"
                    @mouseover="hoverDesc = true"
                    @mouseleave="hoverDesc = false"
                >
                    Description
                </label>
                <div id="description" class="rounded">
                    <textarea
                        placeholder="Description"
                        :class="[
                            status > 2 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700',
                            hoverDesc ? 'border-gray-500' : 'border-gray-200',
                        ]"
                        class="
                            w-full
                            border
                            transition
                            duration-150
                            ease-out
                            hover:border-gray-500
                            rounded
                            py-2
                            px-4
                            focus:outline-none
                        "
                        id="description"
                        @focus="hoverDesc = true"
                        @blur="hoverDesc = false"
                        v-model.trim="$v.description.$model"
                        :disabled="status > 2"
                        required
                    ></textarea>
                </div>
                <p class="text-gray-700 text-xs italic">
                    Give more detail about this survey. This gives Workers a bit more information before they decide to
                    view your survey.
                </p>
            </div>
        </div>
        <hr class="solid mb-4" />
        <div class="-mx-3 md:flex md:flex-col xl:flex-row xl:justify-around">
            <div class="w-full xl:w-1/2">
                <div class="w-full lg:w-auto px-3 mb-4">
                    <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="reward">
                        Reward per response (in $)
                    </label>
                    <input
                        :class="
                            status > 1 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'
                        "
                        class="
                            appearance-none
                            block
                            w-full
                            sm:max-w-xs
                            border border-gray-200
                            rounded
                            py-2
                            px-4
                            mb-2
                            transition
                            duration-150
                            ease-out
                            focus:outline-none focus:border-gray-500
                            hover:border-gray-500
                        "
                        id="reward"
                        type="number"
                        :min="$store.state.defaults.min_reward"
                        step="0.01"
                        placeholder="0.01 $"
                        v-model.trim="$v.reward.$model"
                        :disabled="status > 1"
                        required
                    />
                    <p class="text-gray-700 text-xs italic">
                        This is how much a Worker will be paid for completing your survey. Consider how long it will
                        take a Worker to complete your survey.
                    </p>
                </div>
                <div class="w-full lg:w-auto px-3 mb-4">
                    <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="workers">
                        Number of respondents
                    </label>
                    <input
                        :class="
                            status > 1 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'
                        "
                        class="
                            appearance-none
                            block
                            w-full
                            sm:max-w-xs
                            border border-gray-200
                            rounded
                            py-2
                            px-4
                            mb-2
                            transition
                            duration-150
                            ease-out
                            focus:outline-none focus:border-gray-500
                            hover:border-gray-500
                        "
                        id="workers"
                        type="number"
                        min="1"
                        step="1"
                        placeholder="1"
                        v-model.trim="$v.workers.$model"
                        :disabled="status > 1"
                        required
                    />
                    <p class="text-gray-700 text-xs italic">
                        How many unique Workers do you want to complete your survey?
                    </p>
                </div>
            </div>
            <div class="w-full xl:w-1/2">
                <div class="w-full lg:w-auto px-3 mb-4">
                    <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="maxTime">
                        Time allotted per Worker
                    </label>
                    <div class="flex flex-col flex-grow sm:flex-row">
                        <input
                            :class="
                                status > 1
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700'
                            "
                            class="
                                appearance-none
                                block
                                w-full
                                sm:max-w-xs
                                border border-gray-200
                                rounded
                                py-2
                                px-4
                                mb-2
                                transition
                                duration-150
                                ease-out
                                focus:outline-none focus:border-gray-500
                                hover:border-gray-500
                            "
                            id="maxTime"
                            type="number"
                            min="1"
                            :max="max1"
                            step="1"
                            placeholder="1"
                            v-model.trim="$v.max_time.$model"
                            :disabled="status > 1"
                            required
                        />
                        <div class="relative mt-1 sm:mt-0 sm:ml-2">
                            <select
                                :class="
                                    status > 1
                                        ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                        : 'bg-gray-100 text-gray-700'
                                "
                                class="
                                    block
                                    appearance-none
                                    w-full
                                    sm:w-32
                                    border border-gray-200
                                    py-2
                                    px-4
                                    pr-8
                                    rounded
                                    transition
                                    duration-150
                                    ease-out
                                    focus:outline-none focus:border-gray-500
                                    hover:border-gray-500
                                "
                                id="selectorMaxTime"
                                v-model="selectTempoMax"
                                @change="fixMax(1)"
                                :disabled="status > 1"
                            >
                                <option selected value="days">Days</option>
                                <option value="hours">Hours</option>
                                <option value="minutes">Minutes</option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 text-xs italic">
                        Maximum time a Worker has to complete the survey. Be generous so that Workers are not rushed.
                    </p>
                </div>
                <div class="w-full lg:w-auto px-3 mb-4">
                    <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="expiry">
                        Survey expires in
                    </label>
                    <div class="flex flex-col flex-grow sm:flex-row">
                        <input
                            :class="
                                status > 1
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700'
                            "
                            class="
                                appearance-none
                                block
                                w-full
                                sm:w-32
                                border border-gray-200
                                rounded
                                py-2
                                px-4
                                mb-2
                                transition
                                duration-150
                                ease-out
                                focus:outline-none focus:border-gray-500
                                hover:border-gray-500
                            "
                            id="expiry"
                            type="number"
                            min="1"
                            :max="max2"
                            step="1"
                            placeholder="1"
                            v-model.trim="$v.expiry.$model"
                            :disabled="status > 1"
                            required
                        />
                        <div class="relative mt-1 sm:mt-0 sm:ml-2">
                            <select
                                :class="
                                    status > 1
                                        ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                        : 'bg-gray-100 text-gray-700'
                                "
                                class="
                                    block
                                    appearance-none
                                    w-full
                                    border border-gray-200
                                    py-2
                                    px-4
                                    pr-8
                                    rounded
                                    transition
                                    duration-150
                                    ease-out
                                    focus:outline-none focus:border-gray-500
                                    hover:border-gray-500
                                "
                                id="selectorExpiry"
                                v-model="selectExpiry"
                                @change="fixMax(2)"
                                :disabled="status > 1"
                            >
                                <option selected value="days">Days</option>
                                <option value="hours">Hours</option>
                                <option value="minutes">Minutes</option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 text-xs italic">
                        Maximum time your survey will be available to Workers on Mechanical Turk.
                    </p>
                </div>
                <div class="w-full lg:w-auto px-3 mb-4">
                    <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="autoApproval">
                        Auto-approve and pay Workers in
                    </label>
                    <div class="flex flex-col flex-grow sm:flex-row">
                        <input
                            :class="
                                status > 1
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700'
                            "
                            class="
                                appearance-none
                                block
                                w-full
                                sm:w-32
                                border border-gray-200
                                rounded
                                py-2
                                px-4
                                mb-2
                                transition
                                duration-150
                                ease-out
                                focus:outline-none focus:border-gray-500
                                hover:border-gray-500
                            "
                            id="autoApproval"
                            type="number"
                            min="1"
                            :max="max3"
                            step="1"
                            placeholder="1"
                            v-model.trim="$v.auto_approve.$model"
                            :disabled="status > 1"
                            required
                        />
                        <div class="relative mt-1 sm:mt-0 sm:ml-2">
                            <select
                                :class="
                                    status > 1
                                        ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                        : 'bg-gray-100 text-gray-700'
                                "
                                class="
                                    block
                                    appearance-none
                                    w-full
                                    border border-gray-200
                                    py-2
                                    px-4
                                    pr-8
                                    rounded
                                    transition
                                    duration-150
                                    ease-out
                                    focus:outline-none focus:border-gray-500
                                    hover:border-gray-500
                                "
                                id="selectorApproval"
                                v-model="selectAutoApprove"
                                @change="fixMax(3)"
                                :disabled="status > 1"
                            >
                                <option selected value="days">Days</option>
                                <option value="hours">Hours</option>
                                <option value="minutes">Minutes</option>
                            </select>
                            <div class="pointer-events-none absolute pin-y pin-r flex items-center px-2 text-gray-900">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-700 text-xs italic">
                        This is the amount of time you have to reject a Worker's assignment after they submit the
                        assignment.
                    </p>
                </div>
            </div>
        </div>
        <hr class="solid mb-4" />
        <div class="-mx-3 md:flex md:flex-col lg:flex-row lg:justify-between lg:gap-2">
            <div class="w-full px-3 mb-4 mx-auto">
                <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="layoutId">Layout ID</label>
                <div class="flex content-center items-center flex-wrap relative sm:max-w-xs">
                    <input
                        :class="
                            status > 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'
                        "
                        class="
                            relative
                            appearance-none
                            block
                            w-full
                            sm:max-w-xs
                            border border-gray-200
                            rounded
                            py-2
                            pl-4
                            pr-10
                            transition
                            duration-150
                            ease-out
                            focus:outline-none focus:border-gray-500
                            hover:border-gray-500
                        "
                        id="layoutId"
                        type="text"
                        placeholder="Layout ID"
                        maxlength="100"
                        :disabled="status > 0"
                        v-model.trim="$v.layout_id.$model"
                        required
                    />
                    <span class="absolute right-0">
                        <button
                            @click="downloadLayoutId"
                            :content="'Load examples per hit\nand params fields\nfor this layout'"
                            v-tippy="{ placement: 'bottom', arrow: false, theme: 'google' }"
                            type="button"
                            class="bg-transparent py-2 px-2 rounded focus:outline-none"
                            style="width: 40px; height: 40px"
                            :class="downloadID ? 'animate__heartBeat' : ''"
                        >
                            <svg style="width: 24px; height: 24px" viewBox="0 0 24 24">
                                <path
                                    d="M7.5,5.6L5,7L6.4,4.5L5,2L7.5,3.4L10,2L8.6,4.5L10,7L7.5,5.6M19.5,15.4L22,14L20.6,16.5L22,19L19.5,17.6L17,19L18.4,16.5L17,14L19.5,15.4M22,2L20.6,4.5L22,7L19.5,5.6L17,7L18.4,4.5L17,2L19.5,3.4L22,2M13.34,12.78L15.78,10.34L13.66,8.22L11.22,10.66L13.34,12.78M14.37,7.29L16.71,9.63C17.1,10 17.1,10.65 16.71,11.04L5.04,22.71C4.65,23.1 4,23.1 3.63,22.71L1.29,20.37C0.9,20 0.9,19.35 1.29,18.96L12.96,7.29C13.35,6.9 14,6.9 14.37,7.29Z"
                                />
                            </svg>
                            <span class="sr-only">Load examples per hit and params fields for this layout</span>
                        </button>
                    </span>
                </div>
                <p class="text-gray-700 text-xs italic mt-2">
                    This is the id of the layout that will be shown to the Worker.
                </p>
            </div>
            <div class="w-full px-3 mb-4 mx-auto">
                <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="params">
                    Examples per HIT
                </label>
                <input
                    :class="status > 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                    class="
                        appearance-none
                        block
                        w-full
                        sm:max-w-xs
                        border border-gray-200
                        rounded
                        py-2
                        px-4
                        mb-2
                        transition
                        duration-150
                        ease-out
                        focus:outline-none focus:border-gray-500
                        hover:border-gray-500
                    "
                    id="params"
                    type="number"
                    min="1"
                    step="1"
                    placeholder="1"
                    :disabled="status > 0"
                    v-model.trim="$v.params.$model"
                    required
                />
                <p class="text-gray-700 text-xs italic">Number of tasks per HIT</p>
            </div>
            <div class="w-full px-3 mb-4 mx-auto">
                <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="params_fields">
                    Parameter fields
                </label>
                <input
                    :class="status > 0 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                    class="
                        appearance-none
                        block
                        w-full
                        sm:max-w-xs
                        border border-gray-200
                        rounded
                        py-2
                        px-4
                        mb-2
                        transition
                        duration-150
                        ease-out
                        focus:outline-none focus:border-gray-500
                        hover:border-gray-500
                    "
                    id="params_fields"
                    type="text"
                    :disabled="status > 0"
                    v-model.trim="$v.params_fields.$model"
                    required
                />
            </div>
        </div>
        <hr class="solid mb-4" />

        <!-- <autocomplete
            :search="search"
            placeholder="Search for a country"
            aria-label="Search for a country"
            :get-result-value="getResultValue"
            :debounce-time="250"
            @submit="handleSubmit"
            class="mb-4"
        ></autocomplete> -->
        <div class="flex flex-col md:grid md:grid-cols-2">
            <div class="mr-4" @keydown.enter.prevent.self>
                <label class="block tracking-wide text-gray-900 text-md font-bold pb-2" for="countries">
                    Locations of Workers
                </label>
                <div class="relative">
                    <ul
                        class="appearance-none max-h-48 w-full overflow-y-scroll absolute bottom-0 border border-black"
                        v-if="tmp.length > 0"
                    >
                        <li
                            v-for="i in tmp"
                            :key="i.cca2"
                            class="bg-white cursor-pointer"
                            @click="add(i)"
                            :class="active == tmp.indexOf(i) ? 'bg-primary text-gray-100' : ''"
                            @mouseover="active = tmp.indexOf(i)"
                            :id="parseInt(tmp.indexOf(i))"
                        >
                            <span class="mx-2"> {{ i.name.common }}</span>
                        </li>
                    </ul>
                </div>
                <input
                    @keyup.up.prevent="scroll('up')"
                    @keyup.down.prevent="scroll('down')"
                    @keyup.enter.stop.prevent.self="add(tmp[active])"
                    v-model="input"
                    placeholder="Location"
                    :disabled="status >= 2"
                    :class="status >= 2 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'"
                    autocomplete="off"
                    id="countries"
                    type="text"
                    class="
                        appearance-none
                        w-full
                        border border-gray-200
                        rounded
                        py-2
                        px-4
                        transition
                        duration-150
                        ease-out
                        focus:outline-none focus:border-gray-500
                        hover:border-gray-500
                    "
                />
                <span class="text-gray-700 text-xs italic mt-2">
                    Your Workers will be picked from the countries you select.
                </span>
                <div v-if="selected.length == 0" class="font-thin w-full mt-6 text-center">No countries selected.</div>
                <div v-else class="flex flex-wrap w-full mb-4 mt-2">
                    <div
                        v-for="country in selected"
                        :key="country.cca2"
                        class="flex justify-center items-center py-1 rounded-full text-gray-700 bg-gray-300 chip mb-1"
                    >
                        <span class="text-sm font-semibold leading-none max-w-full flex-initial cursor-default">{{
                            country.name.common
                        }}</span>
                        <button
                            class="focus:outline-none"
                            :disabled="status >= 2"
                            :class="status >= 2 ? 'cursor-not-allowed' : ''"
                            @click="deleteSelected(country)"
                            @keydown.enter.prevent.self
                        >
                            <svg style="width: 21px; height: 21px" viewBox="0 0 24 24">
                                <path
                                    fill="rgba(74, 85, 104, 1)"
                                    d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="md:ml-4">
                <div class="flex flex-row justify-between items-center my-4">
                    <div class="flex flex-col">
                        <label class="tracking-wide text-gray-900 text-md font-bold mr-4" for="master">
                            Only Master Workers:
                        </label>
                        <span class="text-gray-700 text-xs italic mt-1">
                            Require that Workers be Masters to do your tasks.
                        </span>
                    </div>
                    <span
                        :disabled="status >= 2"
                        :class="
                            status >= 2 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'
                        "
                        class="
                            bg-white
                            border-2
                            rounded
                            border-gray-400
                            w-5
                            h-5
                            flex flex-shrink-0
                            focus-within:border-blue-500
                        "
                    >
                        <input
                            id="master"
                            :disabled="status >= 2"
                            :class="
                                status >= 2
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700'
                            "
                            type="checkbox"
                            class="opacity-0 absolute w-4 h-4"
                            @click="qualificationMaster == 1 ? (qualificationMaster = 0) : (qualificationMaster = 1)"
                        />
                        <svg
                            :class="qualificationMaster == 1 ? '' : 'hidden'"
                            class="fill-current w-4 h-4 text-secondary pointer-events-none"
                            viewBox="0 0 20 20"
                        >
                            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                        </svg>
                    </span>
                </div>
                <div class="flex flex-row justify-between my-4">
                    <div class="flex flex-col">
                        <label class="tracking-wide text-gray-900 text-md font-bold mr-4" for="adult">
                            Project contains adult content:
                        </label>
                        <p class="text-gray-700 text-xs italic mt-1">
                            Require that Workers be over 18 years of age to do your tasks.
                        </p>
                    </div>
                    <span
                        :class="
                            status >= 2 ? 'bg-gray-400 text-gray-800 cursor-not-allowed' : 'bg-gray-100 text-gray-700'
                        "
                        :disabled="status >= 2"
                        class="
                            bg-white
                            border-2
                            rounded
                            border-gray-400
                            w-5
                            h-5
                            flex flex-shrink-0
                            focus-within:border-blue-500
                        "
                    >
                        <input
                            id="adult"
                            :disabled="status >= 2"
                            :class="
                                status >= 2
                                    ? 'bg-gray-400 text-gray-800 cursor-not-allowed'
                                    : 'bg-gray-100 text-gray-700'
                            "
                            type="checkbox"
                            class="opacity-0 absolute w-4 h-4"
                            @click="qualificationAdult == 1 ? (qualificationAdult = 0) : (qualificationAdult = 1)"
                        />
                        <svg
                            :class="qualificationAdult == 1 ? '' : 'hidden'"
                            class="fill-current w-4 h-4 text-secondary pointer-events-none"
                            viewBox="0 0 20 20"
                        >
                            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
        <div class="w-full flex justify-start md:justify-end flex-row">
            <button
                type="submit"
                class="
                    ripple
                    mr-1
                    hover:bg-primaryDark
                    bg-primary
                    py-2
                    px-4
                    rounded
                    transition
                    duration-100
                    ease-out
                    text-white
                    focus:outline-none
                "
                v-if="mode == 'edit'"
            >
                <svg
                    :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
                    style="width: 24px; height: 24px"
                    viewBox="0 0 24 24"
                >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                </svg>
                <span v-if="!loading">Save changes</span>
            </button>
            <button
                type="submit"
                :disabled="disableBtn"
                :class="
                    disableBtn
                        ? 'cursor-not-allowed bg-gray-400 text-gray-800 hover:bg-gray-600'
                        : 'ripple hover:bg-primaryDark bg-primary text-white'
                "
                class="mr-1 py-2 px-4 rounded transition duration-100 ease-out focus:outline-none"
                v-else
            >
                <svg
                    :class="loading ? 'animate-spin mr-1 fill-current' : 'hidden'"
                    style="width: 24px; height: 24px"
                    viewBox="0 0 24 24"
                >
                    <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                </svg>
                <span v-if="!loading">Save</span>
            </button>
            <button
                @click="goBack"
                type="button"
                class="
                    ripple
                    bg-transparent
                    hover:bg-gray-300
                    py-2
                    px-4
                    text-gray-900
                    transition
                    border-2 border-solid border-gray-400
                    duration-100
                    ease-out
                    rounded
                    ml-1
                    focus:outline-none
                "
            >
                Cancel
            </button>
        </div>
    </form>
</template>

<script>
import axios from 'axios'
const { required } = require('vuelidate/lib/validators')

export default {
    name: 'creazioneProgetto',
    data() {
        return {
            downloadID: false,
            hoverDesc: false,
            id: '',
            name: '',
            title: '',
            description: '',
            keywords: '',
            reward: 0,
            workers: 0,
            max_time: 1,
            max_time_send: 1,
            expiry: 1,
            expiry_send: 1,
            auto_approve: 1,
            auto_approve_send: 1,
            layout_id: '',
            params: 0,
            params_fields: '',
            /*
            pageTitle: '',
            name: '',
            title: '',
            description: '',
            keywords: '',
            reward: 0.05,
            workers: 5,
            max_time: 0,
            max_time_send: 0,
            expiry: 0,
            expiry_send: 0,
            auto_approve: 0,
            auto_approve_send: 0,
            layout_id: '',
            params: 1,
            */
            selectTempoMax: 'hours',
            selectExpiry: 'days',
            selectAutoApprove: 'days',
            max1: 23,
            max2: 364,
            max3: 364,
            mode: 'new',
            loading: false,
            status: 0,
            disableBtn: true,
            countries: [],
            qualificationMaster: 0,
            qualificationAdult: 0,
            input: '',
            tmp: [],
            selected: [],
            selectedCodes: [],
            active: 0,
            loadingPage1: true,
            loadingPage2: true,
        }
    },
    validations() {
        return {
            params_fields: {
                required,
            },
            name: {
                required,
            },
            title: {
                required,
            },
            description: {
                required,
            },
            keywords: {
                required,
            },
            workers: {
                required,
            },
            max_time: {
                required,
            },
            expiry: {
                required,
            },
            auto_approve: {
                required,
            },
            params: {
                required,
            },
            reward: {
                required,
            },
            layout_id: {
                required,
            },
        }

        /*
        title: {
            required,
            minLength: minLength(2),
        },
        description: {
            required,
            minLength: minLength(2),
        },
        keywords: {
            required,
            minLength: minLength(2),
        },
        */
    },
    created() {
        this.mode = this.$route.name
        if (this.$route.path == '/create') {
            this.getCountries()
            this.mode = this.$route.name
            this.pageTitle = 'Create new project'
            this.disableBtn = false
            this.expiry = parseInt(this.$store.state.defaults.survey_expiration)
            this.max_time = parseInt(this.$store.state.defaults.time_per_worker)
            this.auto_approve = parseInt(this.$store.state.defaults.auto_approve)
            this.reward = parseFloat(this.$store.state.defaults.reward)
            this.workers = parseInt(this.$store.state.defaults.respondents)
            this.params = parseInt(this.$store.state.defaults.examples_per_hit)
            this.elaboraTempoGET('expiry')
            this.elaboraTempoGET('max_time')
            this.elaboraTempoGET('auto_approve')
            this.loadingPage1 = false
            this.loadingPage2 = false
        } else {
            this.pageTitle = 'Edit project ' + this.$route.params.projectId
            this.getCountries()
        }
    },
    mounted() {
        window.addEventListener('keydown', this.keyboardEvent)
    },
    methods: {
        add(country) {
            //console.log(country)
            if (this.tmp.length > 0) {
                this.selected.push(country)
                this.selectedCodes.push(country.cca2)
                var index = this.countries.indexOf(country)
                this.countries.splice(index, 1)
                this.tmp = []
                this.input = ''
            }
        },
        deleteSelected(country) {
            console.log(country)
            var index = this.selected.indexOf(country)
            this.selected.splice(index, 1)
            var index1 = this.selectedCodes.indexOf(country.cca2)
            this.selectedCodes.splice(index1, 1)
            this.countries.push(country)
        },
        scroll(where) {
            if (where == 'up' && this.active > 0) {
                this.active--
            } else if (where == 'down' && this.active < this.tmp.length - 1) {
                this.active++
            }
            document.getElementById(this.active).scrollIntoView()
        },
        // filter() {
        //     console.log(this.input)
        //     if (this.input.length > this.input.length && this.tmp.length > 0) {
        //     } else {
        //         this.tmp = this.countries.filter(country => {
        //             return country.name.toLowerCase().startsWith(this.input)
        //         })
        //     }
        // },
        // search(input) {
        //     if (input.length < 1) {
        //         return []
        //     }
        //     return this.countries.filter(country => {
        //         return country.name.toLowerCase().startsWith(input.toLowerCase())
        //     })
        // },
        // getResultValue(result) {
        //     return result.name
        // },

        getCountries() {
            axios({
                url: 'https://restcountries.com/v3.1/all',
                method: 'get',
            })
                .then(res => {
                    this.countries = res.data
                    this.loadingPage2 = false
                })
                .then(() => {
                    if (this.$route.name == 'edit') {
                        this.getDatiPrj()
                    }
                })
                .catch(err => {
                    console.error(err)
                })
        },
        downloadLayoutId() {
            this.downloadID = true
            this.API()
                .get('?action=testLayout&layout_id=' + this.layout_id)
                .then(res => {
                    this.downloadID = false
                    this.params_fields = res.data.params_fields
                    this.params = parseInt(res.data.examples_per_hit)
                })
        },
        keyboardEvent(event) {
            if (event.code == 'Escape') {
                this.goBack()
            }
        },
        emitSnackbar(msg) {
            this.$emit('snackbar', msg)
        },
        getDatiPrj() {
            this.API()
                .get('?action=getProjectInfo&id=' + this.$route.params.projectId)
                .then(res => {
                    this.disableBtn = false
                    this.id = res.data.values.id
                    this.name = res.data.values.name
                    this.title = res.data.values.title
                    this.description = res.data.values.description
                    this.keywords = res.data.values.keywords
                    this.reward = res.data.values.reward
                    this.max_time = parseInt(res.data.values.max_time)
                    this.expiry = parseInt(res.data.values.expiry)
                    this.auto_approve = parseInt(res.data.values.auto_approve)
                    this.layout_id = res.data.values.layout_id
                    this.workers = res.data.values.workers
                    this.params = res.data.values.params
                    this.status = res.data.values.status
                    this.params_fields = res.data.values.params_fields
                    if (res.data.values.countries != undefined) {
                        this.selectedCodes = res.data.values.countries
                    } else {
                        this.selectedCodes = []
                    }
                    this.qualificationMaster = res.data.values.master
                    this.qualificationAdult = res.data.values.adult
                    this.elaboraTempoGET('max_time')
                    this.elaboraTempoGET('expiry')
                    this.elaboraTempoGET('auto_approve')
                    for (let i = 0; i < this.selectedCodes.length; i++) {
                        this.selected.push(
                            this.countries.find(country => {
                                return country.cca2 == this.selectedCodes[i]
                            })
                        )
                    }
                    this.loadingPage1 = false
                })
                .then(() => {
                    this.fixMax(1)
                    this.fixMax(2)
                    this.fixMax(3)
                })
                .catch(err => {
                    var msg = 'Error. Project not found'
                    this.emitSnackbar(msg)
                    console.error(err)
                })
        },
        caricaProgetto() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                console.log('ok')
                this.loading = true
                this.parseNumbers()
                this.elaboraTempo('max_time')
                this.elaboraTempo('expiry')
                this.elaboraTempo('auto_approve')
                var url = '?action=addProject'
                if (this.mode == 'edit') {
                    url = url + '&id=' + this.id
                }
                this.API()
                    .post(
                        url,
                        {
                            name: this.name,
                            title: this.title,
                            description: this.description,
                            keywords: this.keywords,
                            reward: this.reward,
                            workers: this.workers,
                            max_time: this.max_time_send,
                            expiry: this.expiry_send,
                            auto_approve: this.auto_approve_send,
                            layout_id: this.layout_id,
                            params: this.params,
                            params_fields: this.params_fields,
                            countries: this.selectedCodes,
                            adult: this.qualificationAdult,
                            master: this.qualificationMaster,
                        },
                        { 'Content-Type': 'application/x-www-form-urlencoded' }
                    )
                    .then(response => {
                        this.loading = false
                        console.log(response.data)
                        if (response.data.result == 'OK') {
                            console.log('Inserimento avvenuto')
                            var msg
                            if (this.mode == 'edit') {
                                msg = 'Edit successful'
                            } else {
                                msg = 'Project created'
                            }
                            this.$router.push({ path: '/', name: 'Home' })
                        } else {
                            console.log('Errore')
                            msg = 'Error. Try Again: ' + response.data.error
                        }
                        this.emitSnackbar(msg)
                    })
                    .catch(() => {
                        this.loading = false
                        var msg = 'Error. Try Again'
                        this.emitSnackbar(msg)
                    })
            } else {
                console.log('error')
            }
        },
        goBack() {
            this.$router.go(-1)
        },
        // goHome() {
        //     var msg
        //     if (this.mode == 'edit') {
        //         msg = 'Edit successful'
        //     } else {
        //         msg = 'Project created'
        //     }
        // },
        elaboraTempoGET(nomeVar) {
            if (nomeVar == 'max_time') {
                if (this.max_time < 60) {
                    this.selectTempoMax = 'minutes'
                } else if (this.max_time < 1440) {
                    this.max_time = this.max_time / 60
                    this.selectTempoMax = 'hours'
                } else {
                    this.max_time = this.max_time / 1440
                    this.selectTempoMax = 'days'
                }
            } else if (nomeVar == 'expiry') {
                if (this.expiry < 60) {
                    this.selectExpiry = 'minutes'
                } else if (this.expiry < 1440) {
                    this.expiry = this.expiry / 60
                    this.selectExpiry = 'hours'
                } else {
                    this.expiry = this.expiry / 1440
                    this.selectExpiry = 'days'
                }
            } else {
                if (this.auto_approve < 60) {
                    this.selectAutoApprove = 'minutes'
                } else if (this.auto_approve < 1440) {
                    this.auto_approve = this.auto_approve / 60
                    this.selectAutoApprove = 'hours'
                } else {
                    this.auto_approve = this.auto_approve / 1440
                    this.selectAutoApprove = 'days'
                }
            }
        },
        //modifica il tempo da minuti a minuti/ore/giorni
        elaboraTempo(nomeVar) {
            if (nomeVar == 'max_time') {
                if (this.selectTempoMax == 'ore') {
                    this.max_time_send = this.max_time * 60
                } else if (this.selectTempoMax == 'days') {
                    this.max_time_send = this.max_time * 1440
                } else {
                    this.max_time_send = this.max_time
                }
            } else if (nomeVar == 'expiry') {
                if (this.selectExpiry == 'hours') {
                    this.expiry_send = this.expiry * 60
                } else if (this.selectExpiry == 'days') {
                    this.expiry_send = this.expiry * 1440
                } else {
                    this.expiry_send = this.expiry
                }
            } else {
                if (this.selectAutoApprove == 'hours') {
                    this.auto_approve_send = this.auto_approve * 60
                } else if (this.selectAutoApprove == 'days') {
                    this.auto_approve_send = this.auto_approve * 1440
                } else {
                    this.auto_approve_send = this.auto_approve
                }
            }
        },
        fixMax(num) {
            switch (num) {
                case 1:
                    if (this.selectTempoMax == 'minutes') {
                        this.max1 = 59
                    } else if (this.selectTempoMax == 'hours') {
                        this.max1 = 23
                    } else if (this.selectTempoMax == 'days') {
                        this.max1 = 364
                    }
                    break
                case 2:
                    if (this.selectExpiry == 'minutes') {
                        this.max2 = 59
                    } else if (this.selectExpiry == 'hours') {
                        this.max2 = 23
                    } else if (this.selectExpiry == 'days') {
                        this.max2 = 364
                    }
                    break
                case 3:
                    if (this.selectAutoApprove == 'minutes') {
                        this.max3 = 59
                    } else if (this.selectAutoApprove == 'hours') {
                        this.max3 = 23
                    } else if (this.selectAutoApprove == 'days') {
                        this.max3 = 364
                    }
                    break
            }
        },
        parseNumbers() {
            this.reward = parseFloat(this.reward)
            this.workers = parseInt(this.workers)
            this.max_time = parseInt(this.max_time)
            this.expiry = parseInt(this.expiry)
            this.auto_approve = parseInt(this.auto_approve)
            this.params = parseInt(this.params)
        },
    },
    beforeDestroy() {
        window.removeEventListener('keydown', this.keyboardEvent)
    },
    // computed: {
    //     selectedIndex: function() {
    //         return this.tmp.length - 1
    //     },
    // },
    watch: {
        input(newValue, oldValue) {
            this.active = 0
            if (newValue.length == 0) {
                this.tmp = []
            } else if (newValue.length > oldValue.length && this.tmp.length > 0) {
                this.tmp = this.tmp.filter(country => {
                    return country.name.common.toLowerCase().startsWith(this.input)
                })
            } else {
                this.tmp = this.countries.filter(country => {
                    return country.name.common.toLowerCase().startsWith(this.input)
                })
            }
        },
        $store() {
            if (this.$route.path == '/create') {
                this.expiry = parseInt(this.$store.state.defaults.survey_expiration)
                this.max_time = parseInt(this.$store.state.defaults.time_per_worker)
                this.auto_approve = parseInt(this.$store.state.defaults.auto_approve)
                this.reward = parseFloat(this.$store.state.defaults.reward)
                this.workers = parseInt(this.$store.state.defaults.assignments)
                this.params = parseInt(this.$store.state.defaults.examples_per_hit)
            }
        },
    },
}
</script>

<style scoped>
hr.solid {
    border-top: 1px solid #bbb;
}
textarea {
    min-height: 78px;
    height: 100px;
}
@-webkit-keyframes heartBeat {
    0% {
        -webkit-transform: scale(1);
        transform: scale(1);
    }

    14% {
        -webkit-transform: scale(1.3);
        transform: scale(1.3);
    }

    28% {
        -webkit-transform: scale(1);
        transform: scale(1);
    }

    42% {
        -webkit-transform: scale(1.3);
        transform: scale(1.3);
    }

    70% {
        -webkit-transform: scale(1);
        transform: scale(1);
    }
}
@keyframes heartBeat {
    0% {
        -webkit-transform: scale(1);
        transform: scale(1);
    }

    14% {
        -webkit-transform: scale(1.3);
        transform: scale(1.3);
    }

    28% {
        -webkit-transform: scale(1);
        transform: scale(1);
    }

    42% {
        -webkit-transform: scale(1.3);
        transform: scale(1.3);
    }

    70% {
        -webkit-transform: scale(1);
        transform: scale(1);
    }
}
.animate__heartBeat {
    -webkit-animation-name: heartBeat;
    animation-name: heartBeat;
    -webkit-animation-duration: calc(1s * 1.3);
    animation-duration: calc(1s * 1.3);
    -webkit-animation-duration: calc(1s * 1.3);
    animation-duration: calc(1s * 1.3);
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
    animation-iteration-count: infinite;
}
@media (max-width: 639px) {
    .-ml-27 {
        margin-left: -4.05rem;
    }
}
.-mt-17 {
    margin-top: -5rem;
}
.chip {
    height: 32px;
    margin-right: 8px;
}
.chip svg {
    margin-left: 8px;
    margin-right: 8px;
}
.chip span {
    margin-left: 12px;
}
</style>
