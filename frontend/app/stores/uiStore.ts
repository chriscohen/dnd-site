export const useUiStore = defineStore('ui', () => {
    const backgroundImage = ref<string>('demon.avif');

    function setBackgroundImage(path: string) {
        backgroundImage.value = path;
    }

    return {
        backgroundImage,
        setBackgroundImage
    }
});
