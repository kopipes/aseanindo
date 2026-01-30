import { reactive, ref, onMounted } from 'vue';
import { Howl } from 'howler';
import { useContext } from "./useContext"
import ringingSoundAudio from "../audio/ringing.mp3"


const state = reactive({
     audioRinging: null,
     audioRingingIsPlay: false
});
const cookie = useContext().cookie

export const useSound = () => {
     const ringing = () => {
          if (!state.audioRinging) {
               state.audioRinging = new Howl({
                    src: ringingSoundAudio,
                    autoplay: true,
                    loop: true
               })
          }
          if (!cookie.get('ringing_audio')) {
               state.audioRinging.play()
               state.audioRingingIsPlay = true
               cookie.set('ringing_audio',true)
          }
          // try {
          //      stopRinging();
          //      setTimeout(() => {
          //           let audio = document.createElement("audio");
          //           audio.id = "sound_beep_call"
          //           audio.autoplay = "true"
          //           audio.loop = "true"
          //           audio.style = { display: "none" }
          //           audio.innerHTML = `<source src="${ringingSoundAudio}" type="audio/mpeg">Your browser does not support the audio element.`
          //           document.querySelector("body").prepend(audio);
          //      }, 0);
          // } catch (err) {
          //      console.log(err)
          // }
     }

     const stopRinging = () => {
          // console.log('STOP RINGING')
          const isRinging = cookie.get('ringing_audio')
          // console.log(isRinging)
          if (isRinging && state.audioRinging) {
               state.audioRinging.stop()
               state.audioRingingIsPlay = false
               cookie.del('ringing_audio')
          }
          // const audio = document.getElementById("sound_beep_call");
          // if (audio) audio.remove()
     }

     return {
          ringing, stopRinging, state
     }
}