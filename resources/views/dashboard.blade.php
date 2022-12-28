<x-app-layout>
    <x-slot name="header">
        <div class="grid grid-cols-4 gap-4">
            @if (in_array(Auth::user()->department, ['Dokter Spesialis']))
            <div class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Absen Dokter') }}
            </div>
            @if ($cek->count() > 0)
            <a href="{{ route('absenpulang',$cek->first()->absenid) }}" class="btn btn-secondary">Selesai Praktik</a>
            @else
            <form action="{{ route('absendok') }}" method="post">
                @csrf
                <div class="input-group">
                    <select name="jadwal" class="select select-success w-full max-w-xs">
                        @foreach ($jadwal as $item)
                        <option value="{{ $item->jadwalid }}">{{ $item->poliklinik }} {{ $item->waktu }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Absen</button>
                </div>
            </form>
            @endif
            @else
            <div class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </div>
            @endif
            @if ($gagal = Session::get('gagal'))
                <div class="alert alert-success shadow-lg">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ $gagal }}</span>
                    </div>
                </div>
            @endif
        </div>

    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Selamat Datang,
                    {{ Auth::user()->name }}
                </div>
                <div class="flex flex-row h-[600px]">
                    <div id="info" class="py-2 px-2 basis-1/2 overflow-y-auto h-full">
                        
                    </div>
                    <div class="py-2 px-2  basis-1/2 h-full">
                        <div  id="chat" class="px-2 overflow-y-auto h-[530px]">
                            
                        </div>
                        <div class="h-1/6">
                            <div class="form-control mt-2">
                                <div class="input-group">
                                    <input type="hidden" name="userid" id="userid" value="{{ Auth::user()->employee }}">
                                    <input type="text" name="pesan" id="pesan" placeholder="Tulis Pesan..." class="input input-bordered w-full" required />
                                    <button id="kirim" class="btn btn-accent btn-square">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                        </svg>  
                                    </button>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<script>
    infoBox = document.querySelector("#info")
    chatBox = document.querySelector("#chat")
    
    $("#pesan").keyup(function(event) {
    if (event.keyCode === 13) {
        $("#kirim").click();
    }
    });
    
    $(document).ready(function() {
            isiChat();
        });

    function isiChat() {
            $.get("{{ route('getchatgroup') }}", {}, function(data, status) {
                $("#chat").html(data);
            });
        }

    function isiInfo() {
        $.get("{{ route('getinfo') }}", {}, function(data, status) {
            $("#info").html(data);
        });
    }

    $("#kirim").click(function() {
        kirimPesan();
    });

    function kirimPesan() {
            var pesan = $("#pesan").val();
            if (pesan != "") {
              $.ajax({
                type: "get",
                url: "{{ route('chatgroup') }}",
                data: "pesan="+pesan,
                success: function(data) {
                    $('#pesan').val('');
                }
            });  
            }
        }
    
    chatBox.onmouseenter = ()=>{
        chatBox.classList.add("active");
    }

    chatBox.onmouseleave = ()=>{
        chatBox.classList.remove("active");
    }

    infoBox.onmouseenter = ()=>{
        infoBox.classList.add("active");
    }

    infoBox.onmouseleave = ()=>{
        infoBox.classList.remove("active");
    }
    
    // $(window).on('load', function() {
    //     scrollInfo();
    //     scrollChat();
    // })
    setInterval(() =>{
        if(!chatBox.classList.contains("active")){
            scrollChat();
            isiChat();
        }
        if(!infoBox.classList.contains("active")){
            scrollInfo();
            isiInfo();
        }
    }, 1000);
    
    function scrollInfo(){
        infoBox.scrollTop = infoBox.scrollHeight;
    }

    function scrollChat(){
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>
        
</x-app-layout>