<?php

namespace App\Http\Resources\API;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = '';
        
        // Check if 'id' exists in the data array
        if (isset($this->data['id'])) {
            // Fetch the booking using the id from the notification data
            $booking = Booking::find($this->data['id']);
            
            // If a booking is found
            if ($booking) {
                // Fetch the user associated with the booking
                $user = User::find($booking->customer_id);
                
                // If a user is found, determine the correct image to use
                if ($user) {
                    $image = $user->login_type !== null 
                        ? $user->social_image 
                        : getSingleMedia($user, 'profile_image', null);
                }
            }
        }

        return [
            'id' => $this->id,
            'read_at' => $this->read_at,
            'profile_image' => $image,
            'created_at' => timeAgoFormate($this->created_at),
            'data' => $this->data,
        ];
    }
}

