{{-- resources/views/livewire/verificar-aforo.blade.php --}}
<div>
    <div>
        <label for="fecha">Fecha de la Reserva:</label>
        <input wire:model="fecha" type="date" class="form-control" id="fecha" name="fecha" required>
    </div>
    <div>
        <label for="hora">Hora de la Reserva:</label>
        <input wire:model="hora" type="time" class="form-control" id="hora" name="hora" required>
    </div>
    <div>
        <button wire:click="verificarAforo" class="btn btn-success">Verificar Aforo</button>
    </div>
    @if ($aforoRestante >= 150)
        <div class="alert alert-danger mt-3" role="alert">
            El aforo máximo de 150 personas ha sido alcanzado para este intervalo de tiempo.
        </div>
    @endif
</div>
