<div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
    <div class="flex flex-1 gap-4 justify-between items-start">
        <h3 class="font-bold">Upcoming Appointments</h3>
        <a href="{{ route("customer.appointments") }}" class="underline hover:text-primary text-caption text-sm ">See all</a>
    </div>
    <x-appointment-card 
        name="Dr. Emily Chen" 
        specialization="Clinical Psychologist" 
        time="12.00 PM" 
        joinRoute="#"
        rescheduleRoute="#"
        isSessionAvailable="true"
        />
        
        <x-appointment-card 
        name="Dr. Michael Tan" 
        specialization="Child Therapist" 
        time="3.30 PM"
        joinRoute="#"
        rescheduleRoute="#"
    />
</div>