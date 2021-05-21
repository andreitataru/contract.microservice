<?php

namespace App\Http\Controllers;
use App\Models\Contract;
use App\Models\HouseContract;
use App\Models\ServiceContract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    //

    public function addContract(Request $request) {  
        //validate incoming request 
        $this->validate($request, [
            'houseId' => 'required',
            'startContract' => 'required',
            'endContract' => 'required',
            'terms' => 'required',
            'price' => 'required',
            'contractType' => 'required',
        ]);
        
        try {
            $contract = new Contract;
            $contract->houseId = $request->houseId;
            $contract->startContract = $request->startContract;
            $contract->endContract = $request->endContract;
            $contract->terms = $request->terms;
            $contract->price = $request->price;
            $contract->save();
            if ($request->contractType == "house"){
                $houseContract = new HouseContract;
                $houseContract->contractId = $contract->id;
                $houseContract->hostId = $request->hostId;
                $houseContract->studentId = $request->studentId;
                $houseContract->save();
                return response()->json(['house contract' => [$contract, $houseContract], 'message' => 'CREATED'], 201);
            }
            else if ($request->contractType == "service"){
                $serviceContract = new ServiceContract;
                $serviceContract->contractId = $contract->id;
                $serviceContract->hostId = $request->hostId;
                $serviceContract->serviceProviderId = $request->serviceProviderId;
                $serviceContract->serviceId = $request->serviceId;
                $serviceContract->save();
                return response()->json(['service contract' => [$contract, $serviceContract], 'message' => 'CREATED'], 201);
            }
            else{
                return response()->json(['message' => 'contract type not found'], 201);
            }
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'addContract Failed' + $e], 409);
        }
    }

    public function getAllContracts(Request $request){
        return response()->json([['Contracts' =>  Contract::all()], ['HouseContracts' =>  HouseContract::all()], ['ServiceContracts' =>  ServiceContract::all()]], 200);
    }

    public function getContractById($id)
    {
        try {
            $contract = Contract::findOrFail($id);
            $houseContract = HouseContract::where('contractId', $contract->id)->first();
            $serviceContract = ServiceContract::where('contractId', $contract->id)->first();
            return response()->json([['contract' => $contract], ['houseContract' => $houseContract], ['serviceContract' => $serviceContract]], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Contract not found!'], 404);
        }

    }

    public function getContractByUserId($id)
    {
        try {
            $houseContractsAsHost = HouseContract::where('hostId', $id)->get();
            $contractsAsStudent = HouseContract::where('studentId', $id)->get();
            $serviceContractAsServiceProvider = ServiceContract::where('serviceProviderId', $id)->get();

            return response()->json([['houseContractsAsHost' => $houseContractsAsHost],['contractsAsStudent' => $contractsAsStudent],['serviceContractAsServiceProvider' => $serviceContractAsServiceProvider]], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'Contract not found!'], 404);
        }

    }

}
