<template>
    <div
        class="position-absolute top-0 d-flex justify-items-center justify-content-center time-count-container rounded-5 mt-5">
        {{ time }}
    </div>
</template>

<script>
export default {
    name: "GameTimeComponent",
    props: ['time']
}
</script>

<style scoped>
.time-count-container {
    left: calc(50% - 60px);
    width: 120px;
    background: white;
    z-index: 10;
    color: #1C00A9;
    font-weight: bold;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    font-size: 2rem;
}
</style>
