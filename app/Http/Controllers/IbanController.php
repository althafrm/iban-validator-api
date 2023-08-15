<?php

namespace App\Http\Controllers;

use App\Models\Iban;
use Illuminate\Http\Request;

class IbanController extends Controller
{
    public function getIbans(Request $request)
    {
        try {
            $perPage = $request->get('perPage', 5); // Default per page value
            $pageNumber = $request->get('page');
            $ibans = Iban::with('user')->paginate($perPage, ['*'], 'page', $pageNumber);

            return response()->json([
                'success' => true,
                'ibans' => $ibans,
                'message' => 'Fetched IBANs successfully'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while retrieving IBANs.'
            ], 500);
        }
    }

    public function validateIban(Request $request)
    {
        try {
            $iban = $request->input('iban');
            $user = $request->user();

            if (empty($iban)) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'error' => 'IBAN not found.',
                ], 400);
            }

            // Validate the IBAN and return the result
            $isValid = $this->isValidIBAN($iban);

            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'error' => 'Invalid IBAN',
                ], 400);
            }

            // Save IBAN in the database if not exists
            $savedIban = Iban::updateOrCreate(
                ['iban' => $iban, 'user_id' => $user->id],
            );

            return response()->json([
                'success' => true,
                'valid' => $isValid,
                'iban' => $savedIban,
                'message' => 'Valid IBAN'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => 'An error occurred while processing the request.'], 500);
        }
    }

    private function isValidIBAN($iban)
    {
        $ibanLengths = config('custom.iban-lengths');

        // Remove spaces and convert to uppercase
        $iban = str_replace(' ', '', strtoupper($iban));

        // Check if the IBAN length is valid for the country
        $countryCode = substr($iban, 0, 2);

        if (
            !isset($ibanLengths[$countryCode]) ||
            strlen($iban) !== $ibanLengths[$countryCode]
        ) {
            return false;
        }

        // Move the first four characters to the end
        $iban = substr($iban, 4) . substr($iban, 0, 4);

        // Replace each letter with two digits (A = 10, B = 11, ..., Z = 35)
        $numericIban = '';

        for ($i = 0; $i < strlen($iban); $i++) {
            $charCode = ord($iban[$i]);

            if ($charCode >= 48 && $charCode <= 57) {
                $numericIban .= $iban[$i];
            } else {
                $numericIban .= ($charCode - 55);
            }
        }

        // Validate the IBAN checksum
        $remainder = gmp_mod($numericIban, '97');

        return $remainder == 1;
    }
}
